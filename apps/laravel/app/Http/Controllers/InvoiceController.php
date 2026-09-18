<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Document;
use App\Models\Service;
use App\Services\DocumentCalculator;
use App\Services\RegisterDocumentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $page = max(1, (int) $request->get('page', 1));

        $query = Document::with('client')
            ->where('type', 'invoice');

        if ($search) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->whereHas('client', fn ($cq) => $cq->where('name', 'like', "%{$s}%"))
                    ->orWhere('doc_number', 'like', "%{$s}%");
            });
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10, ['*'], 'page', $page);

        $mappedData = [
            'data' => $invoices->map(fn ($i) => [
                'id' => $i->id,
                'doc_number' => $i->doc_number,
                'client_name' => $i->client->name ?? '—',
                'date' => $i->date ? $i->date->format('d/m/Y') : '—',
                'total' => $i->total,
                'paid' => $i->paid,
                'balance' => $i->balance,
                'status' => $i->status,
            ]),
            'meta' => [
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'total' => $invoices->total(),
            ],
        ];

        if ($request->wantsJson()) {
            return response()->json($mappedData);
        }

        return view('invoices.index', [
            'initialInvoices' => $mappedData['data'],
            'initialMeta' => $mappedData['meta'],
        ]);
    }

    /** Página para crear una factura */
    public function create()
    {
        return view('invoices.form', [
            'mode' => 'create',
            'document' => null,
            'payments' => [],
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    /** Página para ver una factura (solo lectura) */
    public function show(Document $document)
    {
        abort_unless($document->type === 'invoice', 404);
        $document->load('items', 'payments');

        return view('invoices.form', [
            'mode' => 'show',
            'document' => $this->formPayload($document),
            'payments' => $this->paymentsList($document),
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    /** Página para editar una factura pendiente sin pagos */
    public function edit(Document $document)
    {
        abort_unless($document->type === 'invoice', 404);
        $this->ensurePendingAndUnpaid($document);
        $document->load('items');

        return view('invoices.form', [
            'mode' => 'edit',
            'document' => $this->formPayload($document),
            'payments' => [],
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    public function formData()
    {
        return response()->json([
            'clients' => $this->clientsList(),
            'services' => $this->servicesList(),
        ]);
    }

    public function editData(Document $document)
    {
        $document->load('items', 'payments');

        return response()->json($this->formPayload($document) + [
            'paid' => (float) $document->paid,
            'total' => (float) $document->total,
            'balance' => (float) $document->balance,
        ]);
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
            'paid' => (float) $document->paid,
            'total' => (float) $document->total,
            'balance' => (float) $document->balance,
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

    private function paymentsList(Document $document)
    {
        return $document->payments()
            ->orderBy('date', 'desc')->orderBy('id', 'desc')
            ->get(['id', 'date', 'amount', 'method', 'notes'])
            ->map(fn ($p) => [
                'id' => $p->id,
                'date' => $p->date->format('d/m/Y'),
                'amount' => (float) $p->amount,
                'method' => $p->method,
                'notes' => $p->notes ?? '',
            ])->values();
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
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $doc = DB::transaction(function () use ($data) {
            $calculator = new DocumentCalculator;
            $subtotal = (float) $calculator->subtotal($data['items']);
            $count = Document::where('type', 'invoice')->count() + 1;

            $doc = Document::create([
                'client_id' => $data['client_id'],
                'type' => 'invoice',
                'status' => 'pending',
                'date' => $data['date'],
                'due_date' => $data['due_date'] ?? null,
                'notes' => $data['notes'] ?? '',
                'subtotal' => $subtotal,
                'tax' => 0,
                'total' => $subtotal,
                'doc_number' => 'FAC-'.str_pad(5800 + $count, 4, '0', STR_PAD_LEFT),
            ]);

            $this->saveItems($doc, $data['items']);

            return $doc;
        });

        return response()->json(['message' => 'Factura creada', 'doc_number' => $doc->doc_number, 'id' => $doc->id]);
    }

    public function update(Request $request, Document $document)
    {
        abort_unless($document->type === 'invoice', 404);
        $this->ensurePendingAndUnpaid($document);

        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.service_id' => ['nullable', 'exists:services,id'],
            'items.*.service_name' => 'required|string|max:255',
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

        return response()->json(['message' => 'Factura actualizada', 'id' => $document->id]);
    }

    public function destroy(Document $document)
    {
        abort_unless($document->type === 'invoice', 404);
        $this->ensurePendingAndUnpaid($document);

        $document->delete();

        return response()->json(['message' => 'Factura eliminada']);
    }

    public function cancel(Document $document)
    {
        abort_unless($document->type === 'invoice', 404);
        $this->ensurePendingAndUnpaid($document);

        $document->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Factura anulada']);
    }

    public function registerPayment(Request $request, Document $document)
    {
        abort_unless($document->type === 'invoice', 404);

        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $service = app(RegisterDocumentPayment::class);
        $service->handle($document, $data);

        return response()->json(['message' => 'Pago registrado']);
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
                    'subtotal' => floatval($item['subtotal'] ?? 0),
                ]);
            }
        }
    }

    private function ensurePendingAndUnpaid(Document $document): void
    {
        abort_unless(
            $document->status === 'pending' && (float) $document->paid === 0.0,
            409,
            'Solo puedes modificar documentos pendientes sin pagos registrados.'
        );
    }
}
