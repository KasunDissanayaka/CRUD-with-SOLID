<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'order_value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $order = $this->orderRepository->createOrder($request->all());

        return response()->json([
            'order_id' => $order->id,
            'process_id' => $order->process_id,
            'status' => 'Order Created'
        ], 201);
    }
}

