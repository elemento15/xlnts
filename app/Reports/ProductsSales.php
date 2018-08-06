<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;
//use App\Sale;
//use App\Client;
use Carbon\Carbon;

class ProductsSales extends \TCPDF
{
    private $type, $date_ini, $date_end;

    public function __construct($request)
    {
        parent::__construct('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 5, PDF_MARGIN_RIGHT);
        $this->SetHeaderMargin(PDF_MARGIN_HEADER);
        $this->SetFooterMargin(PDF_MARGIN_FOOTER);

        $this->SetFillColor(240, 240, 240);

        $this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150)));

        $this->type = $request->type;
        $this->date_ini = $request->date_ini.' 00:00:00';
        $this->date_end = $request->date_end.' 23:59:59';
    }

    public function Header()
    {
        $border = 0;
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 0, 'Productos mas Vendidos', $border, 1, 'C');
        
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 0, $this->getReportType(), $border, 1, 'C');

        $x = $this->GetX();
        $y = $this->GetY() + 2;

        $this->Line($x, $y, 195, $y);
    }

    public function printPDF()
    {
        $this->SetTitle('Productos mas Vendidos');
        $this->AddPage();

        switch ($this->type) {
            case 1 : $this->printProductsRpt(); break;
            case 2 : $this->printGroupsRpt(); break;
            case 3 : $this->printProductsByGroupRpt(); break;
        }

        $this->Output("productos_vendidos.pdf");
    }


    private function printProductsRpt()
    {
        $border = 0;
        $data = $this->getData();
        $fill = false;
        
        // sub header
        $this->SetFont('helvetica', 'B', 8);
        
        $this->Cell(10, 0, '', false, 0);
        $this->Cell(85, 0, 'Producto', 'B', 0, '');
        $this->Cell(55, 0, 'Grupo', 'B', 0, '');
        $this->Cell(20, 0, 'Cantidad', 'B', 0, 'C');
        $this->Cell(0, 0, '', false, 1);


        $this->SetFont('helvetica', '', 9);

        foreach ($data as $item) {
            $this->Cell(10, 5, '', $border, 0);
            $this->Cell(85, 5, $item->product_name, $border, 0, '', $fill);
            $this->Cell(55, 5, $item->group_name, $border, 0, '', $fill);
            $this->Cell(20, 5, number_format($item->total, 2), $border, 0, 'R', $fill);
            $this->Cell(0, 5, '', $border, 1);

            $fill = !$fill;
        }
    }

    private function printGroupsRpt()
    {
        $border = 0;
        $data = $this->getData();
        $fill = false;
        
        // sub header
        $this->SetFont('helvetica', 'B', 8);
        
        $this->Cell(50, 0, '', false, 0);
        $this->Cell(60, 0, 'Grupo', 'B', 0, '');
        $this->Cell(20, 0, 'Cantidad', 'B', 0, 'C');
        $this->Cell(0, 0, '', false, 1);


        $this->SetFont('helvetica', '', 9);

        foreach ($data as $item) {
            $this->Cell(50, 5, '', $border, 0);
            $this->Cell(60, 5, $item->group_name, $border, 0, '', $fill);
            $this->Cell(20, 5, number_format($item->total, 2), $border, 0, 'R', $fill);
            $this->Cell(0, 5, '', $border, 1);

            $fill = !$fill;
        }
    }

    private function printProductsByGroupRpt()
    {
        $border = 0;
        $data = $this->getData();
        $group = '';

        foreach ($data as $item) {
            if ($group != $item->group_name) {
                $this->Ln();

                $this->SetFont('helvetica', 'B', 10);
                $this->Cell(0, 0, 'Grupo: '.$item->group_name, 'B', 1);

                // sub header
                $this->SetFont('helvetica', 'B', 8);
                
                $this->Cell(10, 0, '', false, 0);
                $this->Cell(110, 0, 'Producto', false, 0, '');
                $this->Cell(20, 0, 'Cantidad', false, 0, 'C');
                $this->Cell(0, 0, '', false, 1);

                $group = $item->group_name;
                $fill = false;
            }

            $this->SetFont('helvetica', '', 9);
            
            $this->Cell(10, 5, '', $border, 0);
            $this->Cell(110, 5, $item->product_name, $border, 0, '', $fill);
            $this->Cell(20, 5, number_format($item->total, 2), $border, 0, 'R', $fill);
            $this->Cell(0, 5, '', $border, 1);

            $fill = !$fill;
        }
    }

    private function getData()
    {
        if ($this->type == 1) {
            $data = DB::table('sale_products as sp')
                      ->leftJoin('sales as s', 's.id', '=', 'sp.sale_id')
                      ->leftJoin('products as p', 'p.id', '=', 'sp.product_id')
                      ->leftJoin('groups as g', 'g.id', '=', 'p.group_id')
                      ->select('p.description AS product_name', 'g.name AS group_name', DB::raw('SUM(sp.quantity) AS total'))
                      ->where('s.sale_date', '>=', $this->date_ini)
                      ->where('s.sale_date', '<=', $this->date_end)
                      ->groupBy('p.id')
                      ->orderBy('total', 'desc');
                      
            $data = $data->get();
        }

        if ($this->type == 2) {
            $data = DB::table('sale_products as sp')
                      ->leftJoin('sales as s', 's.id', '=', 'sp.sale_id')
                      ->leftJoin('products as p', 'p.id', '=', 'sp.product_id')
                      ->leftJoin('groups as g', 'g.id', '=', 'p.group_id')
                      ->select('g.name AS group_name', DB::raw('SUM(sp.quantity) AS total'))
                      ->where('s.sale_date', '>=', $this->date_ini)
                      ->where('s.sale_date', '<=', $this->date_end)
                      ->groupBy('g.id')
                      ->orderBy('total', 'desc');
                      
            $data = $data->get();
        }

        if ($this->type == 3) {
            $data = DB::table('sale_products as sp')
                      ->leftJoin('sales as s', 's.id', '=', 'sp.sale_id')
                      ->leftJoin('products as p', 'p.id', '=', 'sp.product_id')
                      ->leftJoin('groups as g', 'g.id', '=', 'p.group_id')
                      ->select('p.description AS product_name', 'g.name AS group_name', DB::raw('SUM(sp.quantity) AS total'))
                      ->where('s.sale_date', '>=', $this->date_ini)
                      ->where('s.sale_date', '<=', $this->date_end)
                      ->groupBy('p.id')
                      ->orderBy('g.name')
                      ->orderBy('total', 'desc');
                      
            $data = $data->get();
        }

        return $data;
    }

    private function getReportType()
    {
        switch ($this->type) {
            case 1 : $type = 'Solo Productos'; break;
            case 2 : $type = 'Solo Grupos'; break;
            case 3 : $type = 'Productos por Grupo'; break;
        }

        return $type;
    }
}