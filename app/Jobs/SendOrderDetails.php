<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\OrderApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(OrderApiService $orderApi): void
    {
        Log::info('SendOrderDetails job processing.', [
            'order_id' => $this->order->id,
        ]);

        try {
            $apiResponse = $orderApi->sendOrderDetails($this->order);
            Log::info('API response received', ['response' => $apiResponse]);
        } catch (\Exception $e) {
            Log::error('Error sending order details to API', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
