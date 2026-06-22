<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function adminOnly(): void
    {
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
    }

    public function index()
    {
        $this->adminOnly();

        $users = User::latest()->get();
        $title = 'Users';

        return view('users.index', compact('users', 'title'));
    }

    public function create()
    {
        $this->adminOnly();

        $title = 'Create User';

        return view('users.create', compact('title'));
    }

    public function store(Request $request)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'role' => 'required|in:admin,staff',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'User account created successfully.');
    }

    public function edit(User $user)
    {
        $this->adminOnly();

        $title = 'Edit User';

        return view('users.edit', compact('user', 'title'));
    }

    public function update(Request $request, User $user)
    {
        $this->adminOnly();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'role' => 'required|in:admin,staff',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'You cannot remove your own admin role.');
        }

        if (
            $user->role === 'admin' &&
            $validated['role'] !== 'admin' &&
            User::where('role', 'admin')->count() <= 1
        ) {
            return back()->with('error', 'You cannot remove the last admin account.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('users.index')
            ->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->adminOnly();

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'You cannot delete the last admin account.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User account deleted successfully.');
    }
}