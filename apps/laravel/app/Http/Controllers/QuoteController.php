<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Service;
use App\Services\DocumentCalculator;
use App\Services\DocumentNumberGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $page = max(1, (int) $request->get('page', 1));

        $query = Document::with('client')
            ->where('type', 'quote');

        if ($search) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$s}%"))
                    ->orWhere('doc_number', 'like', "%{$s}%");
            });
        }

        $quotes = $query->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $page);

        $mappedData = [
            'data' => $quotes->map(fn ($q) => [
                'id' => $q->id,
                'doc_number' => $q->doc_number,
                'client_name' => $q->client->name ?? '—',
                'date' => $q->date ? $q->date->format('d/m/Y') : '—',
                'total' => $q->total,
                'status' => $q->status,
            ]),
            'meta' => [
                'current_page' => $quotes->currentPage(),
                'last_page' => $quotes->lastPage(),
                'total' => $quotes->total(),
                'per_page' => $quotes->perPage(),
                'from' => $quotes->firstItem(),
                'to' => $quotes->lastItem(),
            ],
        ];

        if ($request->wantsJson()) {
            return response()->json($mappedData);
        }

        return view('quotes.index', [
            'initialQuotes' => $mappedData['data'],
            'initialMeta' => $mappedData['meta'],
        ]);
    }

    /** Página para crear una cotización */
    public function create()
    {
        return view('quotes.form', [
            'mode' => 'create',
            'document' => null,
            'payments' => [],
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    /** Página para ver una cotización (solo lectura) */
    public function show(Document $document)
    {
        abort_unless($document->type === 'quote', 404);
        $document->load('items');

        return view('quotes.form', [
            'mode' => 'show',
            'document' => $this->formPayload($document),
            'payments' => [],
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    /** Página para editar una cotización pendiente */
    public function edit(Document $document)
    {
        abort_unless($document->type === 'quote', 404);
        $this->ensurePending($document);
        $document->load('items');

        return view('quotes.form', [
            'mode' => 'edit',
            'document' => $this->formPayload($document),
            'payments' => [],
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    /** Clientes y servicios para el formulario */
    public function formData()
    {
        return response()->json([
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    /** Datos de una cotización para el formulario de edición */
    public function editData(Document $document)
    {
        abort_unless($document->type === 'quote', 404);
        $document->load('items');

        return response()->json($this->formPayload($document));
    }

    private function clientsList()
    {
        return Client::orderBy('name')->get(['id', 'name']);
    }

    private function servicesList()
    {
        return Service::query()
            ->whereNotNull('name')->where('name', '!=', '')
            ->orderBy('name')->get(['id', 'name', 'unit_price', 'description']);
    }

    private function formPayload(Document $document): array
    {
        return [
            'id' => $document->id,
            'doc_number' => $document->doc_number,
            'client_id' => $document->client_id,
            'date' => $document->date->format('Y-m-d'),
            'due_date' => $document->due_date?->format('Y-m-d') ?? '',
            'notes' => $document->notes,
            'status' => $document->status,
            'items' => $document->items->map(fn ($i) => [
                'service_id' => $i->service_id,
                'service_name' => $i->service_name,
                'description' => $i->description ?? '',
                'quantity' => (float) $i->quantity,
                'unit_price' => (float) $i->unit_price,
                'subtotal' => (float) $i->subtotal,
            ]),
        ];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => ['nullable', 'exists:services,id'],
            'items.*.service_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string|max:2000',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $doc = app(DocumentNumberGenerator::class)->create('quote', function (string $docNumber) use ($data) {
            $calculator = new DocumentCalculator;
            $subtotal = (float) $calculator->subtotal($data['items']);

            $doc = Document::create([
                'client_id' => $data['client_id'],
                'type' => 'quote',
                'status' => 'pending',
                'date' => $data['date'],
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? '',
                'subtotal' => $subtotal,
                'tax' => 0,
                'total' => $subtotal,
                'doc_number' => $docNumber,
            ]);

            $this->saveItems($doc, $data['items']);

            return $doc;
        });

        return response()->json(['message' => 'Cotización creada', 'doc_number' => $doc->doc_number, 'id' => $doc->id]);
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->type === 'quote', 404);
        $this->ensurePending($document);

        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => ['nullable', 'exists:services,id'],
            'items.*.service_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string|max:2000',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($data, $document) {
            $calculator = new DocumentCalculator;
            $subtotal = (float) $calculator->subtotal($data['items']);

            $document->update([
                'client_id' => $data['client_id'],
                'date' => $data['date'],
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? '',
                'subtotal' => $subtotal,
                'tax' => 0,
                'total' => $subtotal,
            ]);

            $document->items()->delete();
            $this->saveItems($document, $data['items']);
        });

        return response()->json(['message' => 'Cotización actualizada', 'id' => $document->id]);
    }

    public function cancel(Document $document)
    {
        abort_unless($document->type === 'quote', 404);
        $this->ensurePending($document);

        $document->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Cotización cancelada']);
    }

    public function destroy(Document $document)
    {
        abort_unless($document->type === 'quote', 404);
        $this->ensurePending($document);

        $document->delete();

        return response()->json(['message' => 'Cotización eliminada']);
    }

    public function duplicate(Document $document)
    {
        abort_unless($document->type === 'quote', 404);
        $this->ensurePending($document);

        $copy = app(DocumentNumberGenerator::class)->create('quote', function (string $docNumber) use ($document) {
            $document->load('items');

            $copy = Document::create([
                'client_id' => $document->client_id,
                'type' => 'quote',
                'status' => 'pending',
                'doc_number' => $docNumber,
                'date' => now()->format('Y-m-d'),
                'due_date' => null,
                'notes' => $document->notes,
                'subtotal' => $document->subtotal,
                'tax' => $document->tax,
                'total' => $document->total,
            ]);

            foreach ($document->items as $item) {
                $copy->items()->create(
                    $item->only(['service_id', 'service_name', 'description', 'quantity', 'unit_price', 'subtotal'])
                );
            }

            return $copy;
        });

        return response()->json(['message' => 'Cotización duplicada como '.$copy->doc_number]);
    }

    public function convertToInvoice(Document $document)
    {
        abort_unless($document->type === 'quote', 404);

        $invoice = app(DocumentNumberGenerator::class)->create('invoice', function (string $docNumber) use ($document) {
            $quote = Document::where('id', $document->id)->lockForUpdate()->first();

            abort_unless($quote->type === 'quote', 404);
            abort_unless($quote->status === 'pending', 409, 'La cotización ya fue procesada.');

            $existing = Document::where('related_doc_id', $quote->id)->where('type', 'invoice')->first();
            if ($existing) {
                abort(409, 'La cotización ya fue convertida.');
            }

            $invoice = Document::create([
                'client_id' => $quote->client_id,
                'type' => 'invoice',
                'date' => now(),
                'due_date' => now()->addDays(30),
                'notes' => $quote->notes,
                'subtotal' => $quote->subtotal,
                'tax' => $quote->tax,
                'total' => $quote->total,
                'status' => 'pending',
                'related_doc_id' => $quote->id,
                'doc_number' => $docNumber,
            ]);

            foreach ($quote->items as $item) {
                $invoice->items()->create(
                    $item->only(['service_id', 'service_name', 'description', 'quantity', 'unit_price', 'subtotal'])
                );
            }

            $quote->update(['status' => 'converted']);

            return $invoice;
        });

        return response()->json(['message' => 'Convertida a Factura #'.$invoice->doc_number]);
    }

    private function saveItems(Document $doc, array $items): void
    {
        foreach ($items as $item) {
            if (! empty(trim($item['service_name'] ?? ''))) {
                $doc->items()->create([
                    'service_id' => ($item['service_id'] ?? null) ?: null,
                    'service_name' => $item['service_name'],
                    'description' => $item['description'] ?? '',
                    'quantity' => floatval($item['quantity'] ?? 1),
                    'unit_price' => floatval($item['unit_price'] ?? 0),
                    'subtotal' => floatval($item['quantity'] ?? 1) * floatval($item['unit_price'] ?? 0),
                ]);
            }
        }
    }

    private function ensurePending(Document $document): void
    {
        abort_unless($document->status === 'pending', 409, 'Solo puedes modificar cotizaciones pendientes.');
    }
}
