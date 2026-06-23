<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\StockTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class RpcController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $id = $request->input('id');

        if ($request->input('jsonrpc') !== '2.0') {
            return $this->error($id, -32600, 'Invalid JSON-RPC version.');
        }

        if (!is_string($request->input('method'))) {
            return $this->error($id, -32600, 'Method is required.');
        }

        $method = $request->input('method');
        $params = $request->input('params', []);

        if (!is_array($params)) {
            return $this->error($id, -32602, 'Params must be an object.');
        }

        try {
            $result = match ($method) {
                'items.list' => $this->itemsList(),
                'items.get' => $this->itemsGet($params),
                'items.create' => $this->itemsCreate($params),
                'stock.record' => $this->stockRecord($params),
                default => null,
            };

            if ($result === null) {
                return $this->error($id, -32601, 'RPC method not found.');
            }

            return $this->success($id, $result);
        } catch (\InvalidArgumentException $e) {
            return $this->error($id, -32602, $e->getMessage());
        } catch (Throwable $e) {
            return $this->error($id, -32000, $e->getMessage());
        }
    }

    private function itemsList(): array
    {
        return Item::with('category')
            ->latest()
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'category' => $item->category?->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'description' => $item->description,
                ];
            })
            ->toArray();
    }

    private function itemsGet(array $params): array
    {
        $data = $this->validateParams($params, [
            'id' => 'required|exists:items,id',
        ]);

        $item = Item::with('category')->findOrFail($data['id']);

        return [
            'id' => $item->id,
            'name' => $item->name,
            'category' => $item->category?->name,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'description' => $item->description,
        ];
    }

    private function itemsCreate(array $params): array
    {
        $data = $this->validateParams($params, [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $item = Item::create([
            'name' => $data['name'],
            'category_id' => $data['category_id'] ?? null,
            'quantity' => $data['quantity'],
            'price' => $data['price'],
            'description' => $data['description'] ?? null,
        ]);

        return [
            'message' => 'Item created successfully through RPC.',
            'item' => $item,
        ];
    }

    private function stockRecord(array $params): array
    {
        $data = $this->validateParams($params, [
            'item_id' => 'required|exists:items,id',
            'type' => 'required|in:stock_in,stock_out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($data) {
            $item = Item::lockForUpdate()->findOrFail($data['item_id']);

            if ($data['type'] === 'stock_out' && $item->quantity < $data['quantity']) {
                throw new \InvalidArgumentException('Insufficient stock for this transaction.');
            }

            if ($data['type'] === 'stock_in') {
                $item->quantity += $data['quantity'];
            } else {
                $item->quantity -= $data['quantity'];
            }

            $item->save();

            $transaction = StockTransaction::create([
                'item_id' => $data['item_id'],
                'user_id' => auth()->id(),
                'type' => $data['type'],
                'quantity' => $data['quantity'],
                'note' => $data['note'] ?? null,
            ]);

            return [
                'message' => 'Stock transaction recorded successfully through RPC.',
                'transaction' => [
                    'id' => $transaction->id,
                    'item_id' => $transaction->item_id,
                    'user_id' => $transaction->user_id,
                    'created_by' => $transaction->user?->name,
                    'type' => $transaction->type,
                    'quantity' => $transaction->quantity,
                    'note' => $transaction->note,
                ],
                'updated_item' => [
                    'id' => $item->id,
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                ],
            ];
        });
    }

    private function validateParams(array $params, array $rules): array
    {
        $validator = Validator::make($params, $rules);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }

        return $validator->validated();
    }

    private function success($id, mixed $result): JsonResponse
    {
        return response()->json([
            'jsonrpc' => '2.0',
            'result' => $result,
            'id' => $id,
        ]);
    }

    private function error($id, int $code, string $message): JsonResponse
    {
        return response()->json([
            'jsonrpc' => '2.0',
            'error' => [
                'code' => $code,
                'message' => $message,
            ],
            'id' => $id,
        ]);
    }
}