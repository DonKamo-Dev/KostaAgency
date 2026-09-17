<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $page = max(1, (int) $request->get('page', 1));

        $query = Service::query();

        if ($search) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $page);

        $mappedData = [
            'data' => $services->map(fn ($s) => [
                'id' => $s->id,
                'name' => $s->name,
                'description' => $s->description,
                'unit_price' => $s->unit_price,
            ]),
            'meta' => [
                'current_page' => $services->currentPage(),
                'last_page' => $services->lastPage(),
                'total' => $services->total(),
            ],
        ];

        if ($request->wantsJson()) {
            return response()->json($mappedData);
        }

        return view('services.index', [
            'initialServices' => $mappedData['data'],
            'initialMeta' => $mappedData['meta'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'unit_price' => 'required|numeric|min:0',
        ]);

        Service::create($data);

        return response()->json(['message' => 'Servicio creado']);
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $service->update($data);

        return response()->json(['message' => 'Servicio actualizado']);
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return response()->json(['message' => 'Servicio eliminado']);
    }
}
