@extends('layouts.app')

{{-- Categories Index --}}
@section('content')
    <x-common.page-breadcrumb pageTitle="Categories" />

    <div class="min-h-screen rounded-2xl border border-gray-200 bg-white px-5 py-7 dark:border-gray-800 dark:bg-white/[0.03] xl:px-10 xl:py-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h3 class="mb-2 font-semibold text-gray-800 text-theme-xl dark:text-white/90 sm:text-2xl">
                    Categories List
                </h3>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Manage all inventory categories.
                </p>
            </div>

            <a href="{{ route('categories.create') }}"
               class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                Add New Category
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if($categories->isEmpty())
            <p class="text-center text-gray-500 dark:text-gray-400">No categories found.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse border border-gray-200 dark:border-gray-700">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800">
                            <th class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300 text-left">Name</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-gray-700 dark:text-gray-300">Description</th>
                            <th class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300 text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $category->name }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300">{{ $category->description ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-gray-700 dark:text-gray-300 text-center">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
                                        Edit
                                    </a>
                                    
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection