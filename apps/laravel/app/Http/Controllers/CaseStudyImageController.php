<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use Symfony\Component\HttpFoundation\Response;

class CaseStudyImageController extends Controller
{
    public function __invoke(CaseStudy $caseStudy): Response
    {
        abort_unless($caseStudy->imagen_data && $caseStudy->imagen_mime, 404);

        $contents = base64_decode($caseStudy->imagen_data, true);
        abort_if($contents === false, 404);

        return response($contents, 200, [
            'Content-Type' => $caseStudy->imagen_mime,
            'Cache-Control' => 'public, max-age=31536000, immutable',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
