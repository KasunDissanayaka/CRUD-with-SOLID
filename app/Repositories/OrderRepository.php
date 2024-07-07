<?php
namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function createOrder(array $data)
    {
        $data['process_id'] = rand(1, 10);
        $order = Order::create($data);
        return $order;
    }
}
