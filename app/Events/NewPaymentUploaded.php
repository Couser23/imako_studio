<?php

namespace App\Events;

use App\Models\Payment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPaymentUploaded implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;

    /**
     * Create a new event instance.
     */
    public function __construct(?Payment $payment = null)
    {
        $this->payment = $payment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin.notifications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'NewPaymentUploaded';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        if ($this->payment) {
            return [
                'id' => $this->payment->id,
                'amount' => number_format($this->payment->amount, 0, ',', '.'),
                'user_name' => $this->payment->booking->user->name ?? 'Pelanggan',
                'package_name' => $this->payment->booking->package->name ?? 'Paket',
                'message' => 'Pesanan masuk',
            ];
        }

        return [
            'id' => rand(100, 999),
            'amount' => '5.000.000',
            'user_name' => 'John Doe (Test)',
            'package_name' => 'Prewedding Premium',
            'message' => 'Pesanan masuk',
        ];
    }
}
