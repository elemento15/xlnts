<?php

namespace App\Reports;

use App\Sale;
use Carbon\Carbon;

class MonthlySales extends \TCPDF
{
    protected $year;


    public function __construct($year)
    {
        parent::__construct('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 5, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->SetFillColor(240, 240, 240);

        $this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150)));

        $this->year = $year;
    }

    public function Header()
    {
        $border = 0;
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 0, 'Ventas Mensuales', $border, 1, 'C');

        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 0, 'Año: '. $this->year, $border, 1, 'C');

        $this->Ln(1);

        $x = $this->GetX();
        $y = $this->GetY() + 2;

        $this->Line($x, $y, 280, $y);
    }

    public function printPDF()
    {
        $this->SetTitle('Ventas Mensuales');
        $this->AddPage();

        $border = 0;
        $this->SetFont('helvetica', 'B', 8);

        $sales = $this->getData();


        // sub header
        $this->Cell(18, 0, '', $border, 0, 'C');
        for ($i=0; $i < 12; $i++) { 
            $this->Cell(20, 0, $this->getMonthName($i + 1), $border, 0, 'C');
        }
        $this->Cell(0, 0, '', 0, 1);

        $this->Ln(1);
        
        $border = true;
        $this->SetFont('helvetica', '', 9);


        $this->Cell(18, 0, 'Subtotal:', $border, 0);
        for ($i=0; $i < 12; $i++) {
            $amount = (isset($sales[$i + 1])) ? number_format($sales[$i + 1]['subtotal'], 2) : '-';
            $this->Cell(20, 0, $amount, $border, 0, 'R');
        }
        $this->Cell(0, 0, '', 0, 1);

        $this->Cell(18, 0, 'Iva:', $border, 0);
        for ($i=0; $i < 12; $i++) {
            $amount = (isset($sales[$i + 1])) ? number_format($sales[$i + 1]['iva'], 2) : '-';
            $this->Cell(20, 0, $amount, $border, 0, 'R');
        }
        $this->Cell(0, 0, '', 0, 1);

        $this->Cell(18, 0, 'Total:', $border, 0);
        for ($i=0; $i < 12; $i++) {
            $amount = (isset($sales[$i + 1])) ? number_format($sales[$i + 1]['total'], 2) : '-';
            $this->Cell(20, 0, $amount, $border, 0, 'R');
        }
        $this->Cell(0, 0, '', 0, 1);


        $this->Output("vtas_mensuales.pdf");
    }

    private function getData()
    {
        $arr_sales = [];
        $start = Carbon::now()->year($this->year)->startOfYear()->toDateString();
        $end   = Carbon::now()->year($this->year)->endOfYear()->toDateString();

        $sales = Sale::where('sale_date', '>=', $start)
                     ->where('sale_date', '<=', $end)
                     ->orderBy('sale_date')
                     ->get();

        foreach ($sales as $sale) {
            $month = intval(substr($sale->sale_date, 5, 2));
            
            if (isset($arr_sales[$month])) {
                $arr_sales[$month]['subtotal'] += $sale->subtotal;
                $arr_sales[$month]['iva'] += $sale->iva_amount;
                $arr_sales[$month]['total'] += $sale->total;
            } else {
                $arr_sales[$month] = [
                    'subtotal' => $sale->subtotal,
                    'iva' => $sale->iva_amount,
                    'total' => $sale->total
                ];
            }
        }

        return $arr_sales;
    }

    private function getMonthName($num)
    {
        switch ($num) {
            case  1 : $month = 'Ene'; break;
            case  2 : $month = 'Feb'; break;
            case  3 : $month = 'Mar'; break;
            case  4 : $month = 'Abr'; break;
            case  5 : $month = 'May'; break;
            case  6 : $month = 'Jun'; break;
            case  7 : $month = 'Jul'; break;
            case  8 : $month = 'Ago'; break;
            case  9 : $month = 'Sep'; break;
            case 10 : $month = 'Oct'; break;
            case 11 : $month = 'Nov'; break;
            case 12 : $month = 'Dic'; break;
        }

        return $month;
    }
}