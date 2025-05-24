<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

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
        $request->validate(['name' => 'required']);
        Service::create($request->only('name'));

        return redirect()->route('services.index');
    }

    public function update(Request $request, Service $service)
    {
        $request->validate(['name' => 'required']);
        $service->update($request->only('name'));

        return redirect()->route('services.index');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index');
    }
}
