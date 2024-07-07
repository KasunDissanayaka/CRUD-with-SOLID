<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Http\Controllers\BaseController as BaseController;

class OrderApiService extends BaseController
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
                Log::error('Failed to send order details to the external API', ['response' => $response->body()]);
                throw new \Exception('External API communication failed - '. $response->body());
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('Error sending order details', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
