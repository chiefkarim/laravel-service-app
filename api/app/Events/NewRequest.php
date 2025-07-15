<?php

namespace App\Events;

use App\Models\ServiceRequest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info('NewRequest broadcastOn method called.', ['request_id' => $this->request->id]);

        return [
            new PrivateChannel('service-requests'),
        ];
    }

    public function broadcastAs(): string
    {
        Log::info('NewRequest broadcastAs method called.', ['event_name' => 'new-request']);

        return 'new-request';
    }

    public function broadcastWith(): array
    {
        $data = [
            'id' => $this->request->id,
            'name' => $this->request->name,
            'service' => $this->request->service,
            'email' => $this->request->email,
            'details' => $this->request->details,
        ];
        Log::info('NewRequest broadcastWith method called.', ['data' => $data]);

        return $data;
    }
}
