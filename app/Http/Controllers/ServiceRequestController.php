<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    public function index()
    {
        return ServiceRequest::all();
    }

    public function show(ServiceRequest $serviceRequest)
    {
        return $serviceRequest;
    }

    public function store(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email',
            'service_id' => 'required|exists:services,id',
            'details' => 'required|string',
        ]);

        ServiceRequest::create($request->only([
            'fullname',
            'email',
            'service_id',
            'details',
        ]));

        return redirect()->route('service-requests.index');
    }

    public function update(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'fullname' => 'required|string',
            'email' => 'required|email',
            'service_id' => 'required|exists:services,id',
            'details' => 'required|string',
            'status' => 'nullable|in:pending,approved,rejected',
            'reply' => 'nullable|string',
        ]);

        $serviceRequest->update($request->only([
            'fullname',
            'email',
            'service_id',
            'details',
            'status',
            'reply',
        ]));

        return redirect()->route('service-requests.index');
    }

    public function destroy(ServiceRequest $serviceRequest)
    {
        $serviceRequest->delete();

        return redirect()->route('service-requests.index');
    }
}
