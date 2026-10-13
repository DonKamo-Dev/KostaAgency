<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::query()->latest();

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%")
                    ->orWhere('servicio', 'like', "%{$search}%")
                    ->orWhere('mensaje', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('tipo_cliente')) {
            $query->where('tipo_cliente', $request->input('tipo_cliente'));
        }

        $leads = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => 0,
            'nuevos' => 0,
            'contactados' => 0,
            'cotizados' => 0,
            'ganados' => 0,
        ];

        try {
            if (Schema::hasTable('leads')) {
                $stats['total'] = Lead::count();
                if (Schema::hasColumn('leads', 'estado')) {
                    $stats['nuevos'] = Lead::where('estado', 'nuevo')->count();
                    $stats['contactados'] = Lead::where('estado', 'contactado')->count();
                    $stats['cotizados'] = Lead::where('estado', 'cotizado')->count();
                    $stats['ganados'] = Lead::where('estado', 'ganado')->count();
                }
            }
        } catch (\Throwable $e) {
            // Safe fallback
        }

        return view('leads.index', compact('leads', 'stats'));
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'estado' => 'required|in:nuevo,contactado,cotizado,ganado,descartado',
            'notas' => 'nullable|string|max:1000',
        ]);

        $lead->update($validated);

        return back()->with('status', 'Estado del prospecto actualizado correctamente.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('status', 'Prospecto eliminado.');
    }
}
