<?php

namespace App\Reports;

// use PDF;

class Kardex extends \TCPDF
{
	protected $product, $details;


	public function __construct()
	{
		parent::__construct(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP - 5, PDF_MARGIN_RIGHT);
		$this->SetHeaderMargin(PDF_MARGIN_HEADER);
		$this->SetFooterMargin(PDF_MARGIN_FOOTER);

		$this->SetFillColor(240, 240, 240);

		$this->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(150, 150, 150)));
	}

	public function setParams($product, $details)
	{
		$this->product = $product;
		$this->details = $details;
	}

	public function Header()
	{
		$border = 0;
		$this->SetFont('helvetica', 'B', 16);
		$this->Cell(0, 0, 'Kardex', $border, 1, 'C');

		$this->Ln(1);
		
		$this->SetFont('helvetica', '', 10);
		$this->Cell(17, 0, 'Producto:', $border);
		$this->Cell(66, 0, $this->product->description, $border);
		$this->Cell(12, 0, 'Grupo:', $border);
		$this->Cell(55, 0, $this->product->group->name, $border);
		$this->Cell(12, 0, 'Precio:', $border);
		$this->Cell(18, 0, '$'.number_format($this->product->price, 2), $border, 1, 'R');

		$x = $this->GetX();
		$y = $this->GetY() + 2;

		$this->Line($x, $y, 195, $y);
	}

	public function print()
	{
		$this->SetTitle('Kardex de Producto');
        $this->AddPage();

        $border = 1;
        $this->SetFont('helvetica', 'B', 8);

        // sub header
        $this->Cell(20, 0, 'Fecha', $border, 0, 'C');
        $this->Cell(10, 0, 'Man', $border, 0, 'C');
        $this->Cell(90, 0, 'Concepto', $border, 0, 'L');
        $this->Cell(18, 0, 'Entrada', $border, 0, 'C');
        $this->Cell(18, 0, 'Salida', $border, 0, 'C');
        $this->Cell(18, 0, 'Existencia', $border, 1, 'C');

        $this->Ln(1);
        
        $border = false;
        $fill = false;
        $stock = 0;

        $this->SetFont('helvetica', '', 9);

        foreach ($this->details as $key => $item) {
        	$mov = $item->movement;

        	if (! $mov->active) continue; // omiting inactive movements

        	// calculate current stock
        	$stock += ($mov->type == 'E') ? $item->quantity : 0;
        	$stock -= ($mov->type == 'S') ? $item->quantity : 0;

        	$this->Cell(20, 5, substr($item->movement->mov_date, 0, 10), $border, 0, 'C', $fill);
        	$this->Cell(10, 5, ($mov->movement_concept->is_auto) ? '' : 'M', $border, 0, 'C', $fill);
        	$this->Cell(90, 5, $mov->movement_concept->name, $border, 0, 'L', $fill);
        	$this->Cell(18, 5, number_format(($mov->type == 'E') ? $item->quantity : 0, 2), $border, 0, 'R', $fill);
        	$this->Cell(18, 5, number_format(($mov->type == 'S') ? $item->quantity : 0, 2), $border, 0, 'R', $fill);
        	$this->Cell(18, 5, number_format($stock, 2), $border, 1, 'R', $fill);

        	$fill = !$fill;
        }

        $this->Output("kardex.pdf");
	}
}