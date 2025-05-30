<?php

namespace App\Http\Controllers;

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

        return ServiceRequest::with(['service'])->get();

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
            'email' => $request->email,
            'name' => $request->name,
            'service_id' => $request->service_id,
            'details' => $request->details,
        ]);
        // TODO: make it more personalized by sending request information
        $serviceRequest->load('service');
        Mail::to($request->email)->send(
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

        Mail::to($serviceRequest->email)->send(
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
