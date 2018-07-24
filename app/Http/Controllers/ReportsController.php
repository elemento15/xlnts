<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reports\MonthlySales;

class ReportsController extends Controller
{
    public function monthlySales($year)
    {
        $pdf = new MonthlySales($year);
        $pdf->printPDF();
    }
}
