<?php

namespace App\Http\Controllers;

use App\Models\AiMetaQuote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class MetaAdsController extends Controller
{
    public function pdf(int $id)
    {
        $quote = AiMetaQuote::findOrFail($id);

        $pdf = Pdf::loadView('pdf.meta-ads-quote', ['quote' => $quote])
            ->setPaper('a4', 'portrait');

        $filename = 'cotizacion-meta-ads-' . Str::slug($quote->client_name) . '-' . $quote->id . '.pdf';

        return $pdf->download($filename);
    }
}
