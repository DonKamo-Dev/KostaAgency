<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function pdf(Document $document)
    {
        $document->load(['items', 'client']);

        $pdf = Pdf::loadView('pdf.quote', compact('document'));

        return $pdf->stream("Documento_{$document->doc_number}.pdf");
    }
}
