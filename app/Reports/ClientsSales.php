<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;
use App\Sale;
use App\Client;
use Carbon\Carbon;

class ClientsSales extends \TCPDF
{
    private $show_general;

    public function __construct($request)
    {
        parent::__construct('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 5, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->SetFillColor(240, 240, 240);

        $this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150)));

        $this->show_general = intval($request->show_general);
    }

    public function Header()
    {
        $border = 0;
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 0, 'Mejores Clientes', $border, 1, 'C');
        
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 0, '', $border, 1, 'C');

        $x = $this->GetX();
        $y = $this->GetY() + 2;

        $this->Line($x, $y, 195, $y);
    }

    public function printPDF()
    {
        $this->SetTitle('Mejores Clientes');
        $this->AddPage();

        $border = 0;
        $total = 0;
        $data = $this->getData();
        $fill = false;
        
        // sub header
        $this->SetFont('helvetica', 'B', 9);
        
        $this->Cell(36, 0, '', $border, 0);
        $this->Cell(80, 0, 'Cliente', 'B', 0, 'C');
        $this->Cell(22, 0, 'Total', 'B', 0, 'C');
        $this->Cell(0, 0, '', $border, 1);


        $this->SetFont('helvetica', '', 10);

        foreach ($data as $item) {
            $this->Cell(36, 5, '', $border, 0);
            $this->Cell(80, 5, $item->name, $border, 0, '', $fill);
            $this->Cell(22, 5, number_format($item->totals, 2), $border, 0, 'R', $fill);
            $this->Cell(0, 5, '', $border, 1);

            $total += $item->totals;
            $fill = !$fill;
        }

        $x = $this->GetX();
        $y = $this->GetY() + 2;
        $this->Line(48, $y, 156, $y);

        // total
        $this->Ln();
        $this->SetFont('helvetica', 'B', 9);

        $this->Cell(36, 0, '', $border, 0);
        $this->Cell(80, 0, 'Total: ', $border, 0, 'R');
        $this->Cell(22, 0, number_format($total, 2), $border, 0, 'R');
        $this->Cell(0, 0, '', $border, 1);


        $this->Output("mejores_clientes.pdf");
    }

    private function getData()
    {
        $data = DB::table('sales as s')
                  ->join('clients as c', 'c.id', '=', 's.client_id')
                  ->select('c.id', 'c.name', DB::raw('SUM(s.total) AS totals'))
                  ->groupBy('c.id')
                  ->orderBy('totals', 'desc');

        if ($this->show_general == 0) {
            $data->where('c.is_general', 0);
        }
                  
        return $data->get();
    }
}