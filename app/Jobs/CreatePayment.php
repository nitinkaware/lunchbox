<?php

namespace App\Jobs;

use App\Http\Requests\PaymentRequest;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePayment implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var array
     */
    private $orderIds;

    public function __construct(array $orderIds)
    {
        $this->orderIds = $orderIds;
    }

    public static function fromRequest(PaymentRequest $request)
    {
        return new self($request->orderIds());
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->orderIds as $orderId) {
            auth()->user()->orders()->updateExistingPivot($orderId, ['paid_at' => now()]);
        }
    }
}
