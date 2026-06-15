<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\OrderIndexRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orders)
    {
    }

    public function index(OrderIndexRequest $request): JsonResponse
    {
        return response()->json($this->orders->search($request->validated()));
    }

    public function store(StoreOrderRequest $request): JsonResponse
    {
        return response()->json($this->orders->placeOrder($request->validated()), Response::HTTP_CREATED);
    }

    public function show(Order $order): JsonResponse
    {
        return response()->json($order->load(['customer', 'services']));
    }

    public function update(UpdateOrderRequest $request, Order $order): JsonResponse
    {
        return response()->json($this->orders->update($order, $request->validated()));
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
