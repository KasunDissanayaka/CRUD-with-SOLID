<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;

class OrderController extends BaseController
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
            // return response()->json(['errors' => $validator->errors()], 400);
            return $this->sendError(['error'=>$validator->errors()]);
        }

        $order = $this->orderRepository->createOrder($request->all());

        return $this->sendResponse($order, 'Order Created successfully.');
        // return response()->json([
        //     'order_id' => $order->id,
        //     'process_id' => $order->process_id,
        //     'status' => 'Order Created'
        // ], 201);
    }
}

