<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function index()
    {

        return Service::all();
    }

    public function show(Service $service)
    {
        return $service;
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-services');

        $request->validate(['name' => 'required']);
        $service = Service::create($request->only('name'));

        return response()->json($service, 201);
    }

    public function update(Request $request, Service $service)
    {
        Gate::authorize('manage-services');

        $request->validate(['name' => 'required']);
        $service->update($request->only('name'));

        return response()->json($service, 201);
    }

    public function destroy(Service $service)
    {
        Gate::authorize('manage-services');

        $service->delete();

        return response()->json(null, 204);
    }
}
