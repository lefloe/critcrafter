<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfTestController extends Controller
{
public function generate()
{
$pdf = Pdf::loadView('test');
return $pdf->download('pali_test.pdf');
}
}
