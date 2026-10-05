<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Resources\ServiceResource;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // $services = Service::paginate(10);
        $query = Service::query();
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->min_price !== null) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price !== null) {
            $query->where('price', '<=', $request->max_price);
        }

        $services = $query->paginate(10);

        return ServiceResource::collection($services);
    }
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
        ]);

        return response()->json([
            'message' => 'Service created successfully',
            'service' => $service,
        ], 201);
    }
    public function show(Service $service){
        return new ServiceResource($service);
    }
}
