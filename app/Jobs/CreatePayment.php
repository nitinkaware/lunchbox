<?php

namespace App\Jobs;

use App\Http\Requests\PaymentRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePayment implements ShouldQueue {

    /**
     * @var int
     */
    private $toUserId;
    /**
     * @var float
     */
    private $amount;

    public function __construct(int $toUserId, float $amount)
    {
        $this->toUserId = $toUserId;
        $this->amount = $amount;
    }

    public static function fromRequest(PaymentRequest $request)
    {
        return new static(
            $request->fromUserId(),
            $request->amount()
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        auth()->user()->payments()->create([
            'to_user_id' => $this->toUserId,
            'amount'     => $this->amount,
        ]);
    }
}
