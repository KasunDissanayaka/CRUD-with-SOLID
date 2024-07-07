<?php
namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data)
    {
        $data['process_id'] = rand(1, 10);
        $order = Order::create($data);
        $response = Http::post('https://wibip.free.beeceptor.com/order', [
            'Order_ID' => $order->id,
            'Customer_Name' => $order->customer_name,
            'Order_Value' => $order->order_value,
            'Order_Date' => $order->created_at->format('Y-m-d H:i:s'),
            'Order_Status' => $order->order_status,
            'Process_ID' => $order->process_id
        ]);
    
        return $order;
    }
}
