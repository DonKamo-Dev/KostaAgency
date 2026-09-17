<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $page = max(1, (int) $request->get('page', 1));

        $query = Client::query();

        if ($search) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('email', 'like', "%{$s}%")
                    ->orWhere('tax_id', 'like', "%{$s}%");
            });
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $page);

        $mappedData = [
            'data' => $clients->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'tax_id' => $c->tax_id,
                'email' => $c->email,
                'phone' => $c->phone,
                'address' => $c->address,
            ]),
            'meta' => [
                'current_page' => $clients->currentPage(),
                'last_page' => $clients->lastPage(),
                'total' => $clients->total(),
            ],
        ];

        if ($request->wantsJson()) {
            return response()->json($mappedData);
        }

        return view('clients.index', [
            'initialClients' => $mappedData['data'],
            'initialMeta' => $mappedData['meta'],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        $client = Client::create($data);

        return response()->json([
            'message' => 'Cliente creado',
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'tax_id' => $client->tax_id,
                'email' => $client->email,
                'phone' => $client->phone,
                'address' => $client->address,
            ],
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
        ]);

        $client->update($data);

        return response()->json(['message' => 'Cliente actualizado']);
    }

    public function destroy(Client $client)
    {
        abort_if($client->documents()->exists(), 409, 'No puedes eliminar un cliente con documentos asociados.');

        $client->delete();

        return response()->json(['message' => 'Cliente eliminado']);
    }
}
