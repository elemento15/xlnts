<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reports\MonthlySales;
use App\Reports\ClientsSales;

class ReportsController extends Controller
{
    public function monthlySales($year)
    {
        $pdf = new MonthlySales($year);
        $pdf->printPDF();
    }

    public function clientsSales(Request $request)
    {
        $pdf = new ClientsSales($request);
        $pdf->printPDF();
    }
}
