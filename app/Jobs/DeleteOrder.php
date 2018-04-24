<?php

namespace App\Jobs;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DeleteOrder implements ShouldQueue
{
    /**
     * @var Order
     */
    private $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        $this->order->users()->detach();
        return $this->order->delete();
    }
}
