<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;
use Illuminate\Support\Facades\Log;

class OrderController extends BaseController
{
    protected $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function create(Request $request)
    {
        try {
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

        } catch (\Exception $e) {

            Log::error('Error creating order', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
            
        }

    }
}

