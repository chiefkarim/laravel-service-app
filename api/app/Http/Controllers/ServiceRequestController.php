<?php

namespace App\Http\Controllers;

use App\Events\NewRequest;
use App\Mail\RequestReceived;
use App\Mail\RequestUpdate;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class ServiceRequestController extends Controller
{
    public function index()
    {

        Gate::authorize('has-permission', ['resource' => 'service-requests', 'operation' => 'read']);

        return ServiceRequest::with(['service'])->latest()->paginate(10);

    }

    public function show(ServiceRequest $serviceRequest)
    {

        Gate::authorize('has-permission', ['resource' => 'service-requests', 'operation' => 'read']);

        return $serviceRequest->load(['service']);

    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'details' => 'required|string',
        ]);

        $serviceRequest = ServiceRequest::create([
            'name' => $request->name,
            'service_id' => $request->service_id,
            'email' => $request->email,
            'details' => $request->details,
        ]);

        $serviceRequest->load('service');

        NewRequest::dispatch($serviceRequest);

        Mail::to($request->email)->queue(
            new RequestReceived($serviceRequest)
        );

        return response()->json($serviceRequest->load(['service']), 201);
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        Gate::authorize('has-permission', ['resource' => 'service-requests', 'operation' => 'update']);

        $request->validate([
            'status' => 'sometimes|required|in:pending,approved,declined',
            'reply' => 'nullable|string',
        ]);

        $serviceRequest->update($request->only(['details', 'status', 'reply']));

        Mail::to($serviceRequest->email)->queue(
            new RequestUpdate($serviceRequest)
        );

        return response()->json($serviceRequest->load(['service']), 200);
    }

    public function destroy(ServiceRequest $serviceRequest)
    {

        Gate::authorize('has-permission', ['resource' => 'service-requests', 'operation' => 'delete']);

        $serviceRequest->delete();

        return response()->json(null, 204);
    }
}
