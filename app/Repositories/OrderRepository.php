<?php
namespace App\Repositories;

use App\Jobs\SendOrderDetails;
use App\Models\Order;
use App\Services\OrderApiService;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Log;

class OrderRepository implements OrderRepositoryInterface
{
    protected $orderApiService;
    protected $db;

    public function __construct(OrderApiService $orderApiService, DatabaseManager $db)
    {
        $this->orderApiService = $orderApiService;
        $this->db = $db;
    }

    public function createOrder(array $data)
    {
        try {
            return $this->db->transaction(function () use ($data) {

                $data['process_id'] = rand(1, 10);
                $order = Order::create($data);
                //$apiResponse = $this->orderApiService->sendOrderDetails($order);
                SendOrderDetails::dispatch($order)->onQueue('orders');
                return [
                    'order' => $order,
                    'apiResponse' => 'Order is created and queued',
                ];

            });

        } catch (\Exception $e) {

            Log::error('Error creating order', ['error' => $e->getMessage()]);
            throw $e;
            
        }
    }
}

