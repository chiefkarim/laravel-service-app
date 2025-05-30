<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public ServiceRequest $request;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->request = $serviceRequest->load('service');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('service-requests'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-request';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->request->id,
            'name' => $this->request->name,
            'service' => $this->request->service,
            'email' => $this->request->email,
            'details' => $this->request->details,
        ];
    }
}
