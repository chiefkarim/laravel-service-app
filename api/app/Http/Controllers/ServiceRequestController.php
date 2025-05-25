<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Gate;

class ServiceRequestController extends Controller
{
    public function index()
    {

        $user = FacadesAuth::user();
        if (Gate::allow('service-request-update')) {

            return ServiceRequest::with(['user', 'service'])->get();

        }

        return ServiceRequest::with(['user', 'service'])->where('user_id', $user->getAuthIdentifier())->get();

    }

    public function show(ServiceRequest $serviceRequest)
    {
        gate::authorize('access-service-request', $serviceRequest);

        return $serviceRequest->load(['user', 'service']);

    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'service_id' => 'required|exists:services,id',
            'details' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if (! $user) {
            $user = User::create([
                'email' => $request->input('email'),
                'name' => $request->input('name'),
                'password' => fake()->password(),
            ]);
        }

        $serviceRequest = ServiceRequest::create([
            'user_id' => $user->id,
            'service_id' => $request->service_id,
            'details' => $request->details,
        ]);

        return response()->json($serviceRequest->load(['user', 'service']), 201);
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {

        Gate::authorize('service-request-update');

        $request->validate([
            'status' => 'sometimes|required|in:pending,approved,declined',
            'reply' => 'nullable|string',
        ]);

        $serviceRequest->update($request->only(['details', 'status', 'reply']));

        return response()->json($serviceRequest->load(['user', 'service']), 200);
    }

    public function destroy(ServiceRequest $serviceRequest)
    {

        gate::authorize('access-service-request', $serviceRequest);
        $serviceRequest->delete();

        return response()->json(null, 204);
    }
}
