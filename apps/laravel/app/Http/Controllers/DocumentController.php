<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    public function pdf(Document $document)
    {
        $document->load(['items', 'client']);

        $view = $document->type === 'bill' ? 'pdf.bill' : 'pdf.quote';
        $pdf = Pdf::loadView($view, compact('document'));

        return $pdf->stream("Documento_{$document->doc_number}.pdf");
    }
}
