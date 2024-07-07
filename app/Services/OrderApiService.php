<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class OrderApiService
{
    public function sendOrderDetails(Order $order)
    {
        try {
            $response = Http::post('https://wibip.free.beeceptor.com/order', [
                'Order_ID' => $order->id,
                'Customer_Name' => $order->customer_name,
                'Order_Value' => $order->order_value,
                'Order_Date' => $order->created_at->format('Y-m-d H:i:s'),
                'Order_Status' => $order->order_status,
                'Process_ID' => $order->process_id
            ]);

            if ($response->failed()) {
                Log::error('Failed to send order details to external service', ['response' => $response->body()]);
                throw new \Exception('External service communication failed');
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Error sending order details', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
