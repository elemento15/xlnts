<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Sale;
use App\SaleProduct;
use App\SaleProductAttribute;
use App\VisitType;
use App\Visit;
use App\Movement;
use App\MovementConcept;
use App\MovementProduct;
use App\Configuration;
use PDF;

class SalesController extends BaseController
{
    protected $mainModel = 'App\Sale';

    // params needen for index
    protected $searchFields = [];
    protected $indexPaginate = 10;
    protected $indexJoins = ['sale_products','client'];
    protected $orderBy = ['field' => 'sale_date', 'type' => 'DESC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    // protected $saveFields = ['name','type'];
    protected $storeFields = ['has_invoice','client_id'];
    protected $updateFields = [];
    protected $defaultNulls = [];
    protected $formRules = [
        'client_id' => 'required'
    ];

    protected $allowDelete = false;
    protected $allowUpdate = false;
    protected $allowStore  = true;
    protected $except = [];


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $mainModel = $this->mainModel;

        $rules = $this->parseFormRules(0);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->errors()->all()), 422);
        } else {            
            try {
                DB::beginTransaction();


                // =============== Create the Visit ===============
                $type = VisitType::findByCode('VTA'); // get visit type

                // create the visit as sale (VTA) type
                $visit = Visit::create([
                    'visit_date' => date('Y-m-d H:i:s'),
                    'visit_type_id' => $type->id,
                    'client_id' => $request->client_id
                ]);

                
                // =============== Create the Sale ===============
                $subtotal = 0;
                $count_prods_sell = 0; // count of products selled
                $count_prods_dev = 0;  // count of products devolve

                $iva = ($config = Configuration::first()) ? $config->iva : 0;
                
                $sale = $mainModel::create([
                    'sale_date' => date('Y-m-d H:i:s'),
                    'client_id' => $request->client_id,
                    'has_invoice' => $request->has_invoice,
                    'iva_percent' => ($request->has_invoice) ? $iva : 0, // TODO: get iva_percent from configurations
                    'comments' => $request->comments
                ]);

                // save products
                foreach ($request->products as $item) {
                    $product = Product::find($item['id']);

                    $product_subtotal = $item['quantity'] * $item['price'];

                    $sale_product = SaleProduct::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $product->id,
                        'product_type'  => $product->type,
                        'description'   => $product->description,
                        'quantity'      => $item['quantity'],
                        'product_price' => $product->price,
                        'saved_price'   => $item['price'],
                        'subtotal'      => ($item['is_devolution']) ? ($product_subtotal * -1) : $product_subtotal,
                        'is_devolution' => $item['is_devolution'],
                    ]);

                    // count sold and devolve products
                    if ($product->type == 'P') {
                        if ($item['is_devolution']) {
                            $count_prods_dev++;
                        } else {
                            $count_prods_sell++;
                        }
                    }

                    // save product's attributes
                    foreach ($item['attributes'] as $attr) {
                        SaleProductAttribute::create([
                            'sale_product_id' => $sale_product->id,
                            'attribute_id' => $attr['attribute_id'],
                            'left_value' => $attr['left_value'],
                            'right_value' => $attr['right_value']
                        ]);
                    }

                    $subtotal += $sale_product->subtotal;
                }

                // set calculated fields for sales
                $sale->subtotal = $subtotal;
                $sale->iva_amount = $subtotal * ($sale->iva_percent / 100); 
                $sale->total = $subtotal + $sale->iva_amount;
                $sale->save();


                // =============== Relate Sale to Visit ===============
                $visit->sale_id = $sale->id;
                $visit->save();


                // =============== Create Movements ===============
                if ($count_prods_sell) {
                    $concept = MovementConcept::findByCode('VTA');

                    $movement = Movement::create([
                        'mov_date' => date('Y-m-d H:i:s'),
                        'movement_concept_id' => $concept->id,
                        'type' => $concept->type,
                        'active' => 1
                    ]);

                    // save movement's products
                    foreach ($request->products as $item) {
                        if ($item['is_devolution']) continue;
                        
                        $product = Product::find($item['id']);

                        if ($product->type == 'P') {
                            $movProd = MovementProduct::create([
                                'movement_id' => $movement->id,
                                'product_id' => $item['id'],
                                'quantity' => $item['quantity']
                            ]);
                        }
                    }

                    // update stock
                    $movement->updateStock();
                }


                if ($count_prods_dev) {
                    $concept = MovementConcept::findByCode('DEV');

                    $movement = Movement::create([
                        'mov_date' => date('Y-m-d H:i:s'),
                        'movement_concept_id' => $concept->id,
                        'type' => $concept->type,
                        'active' => 1
                    ]);

                    // save movement's products
                    foreach ($request->products as $item) {
                        if (! $item['is_devolution']) continue;

                        $product = Product::find($item['id']);

                        if ($product->type == 'P') {
                            $movProd = MovementProduct::create([
                                'movement_id' => $movement->id,
                                'product_id' => $item['id'],
                                'quantity' => $item['quantity']
                            ]);
                        }
                    }

                    // update stock
                    $movement->updateStock();
                }

                // =============== End Create Movements ===============

                DB::commit();

                return $sale;
            } catch (Exception $e) {
                return Response::json(array('msg' => 'Error al guardar'), 500);
            }
        }
    }

    public function showPdf($id)
    {
        $sale = Sale::with('sale_products')->with('client')->find($id);

        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetTitle('Impresión de Venta');
        $pdf->AddPage();

        
        // Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $border = 0;
        $pdf->SetFillColor(222, 222, 222);

        $pdf->SetFont('times', '', 10);
        $pdf->Cell(20, 0, 'Cliente: ', $border, 0, '');
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(170, 0, $sale->client->name, $border, 1, '');
        
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(20, 0, 'RFC: ', $border, 0, '');
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(40, 0, $sale->client->rfc, $border, 0, '');
        $pdf->Cell(95, 0, '', $border, 0, '');
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(15, 0, 'Fecha: ', $border, 0, '');
        $pdf->SetFont('times', 'B', 10);
        $pdf->Cell(20, 0, substr($sale->sale_date, 0, 10), $border, 1, '');

        $pdf->Ln(5);
        $border = 1;

        // products
        $pdf->SetFont('times', 'B', 10);
        
        $pdf->Cell(15, 0, 'Cant.', $border, 0, 'C', true);
        $pdf->Cell(125, 0, 'Descripción', $border, 0, 'C', true);
        $pdf->Cell(25, 0, 'Precio', $border, 0, 'C', true);
        $pdf->Cell(25, 0, 'Importe', $border, 1, 'C', true);
        
        $pdf->SetFont('times', '', 10);
        
        foreach ($sale->sale_products as $key => $item) {
            $pdf->Cell(15, 0, number_format($item->quantity, 2), $border, 0, 'R');
            $pdf->Cell(125, 0, $item->description, $border, 0, '');
            $pdf->Cell(25, 0, number_format($item->saved_price, 2), $border, 0, 'R');
            $pdf->Cell(25, 0, number_format($item->subtotal, 2), $border, 1, 'R');
        }

        $pdf->Ln(2);
        $y = $pdf->GetY(); // save current Y position

        if ($sale->has_invoice) {
            // subtotal and IVA
            $pdf->Cell(140, 0, '', false, 0, '');
            $pdf->Cell(25, 0, 'Subtotal: ', $border, 0, 'C', true);
            $pdf->Cell(25, 0, number_format($sale->subtotal, 2), $border, 1, 'R');

            $pdf->Cell(140, 0, '', false, 0, '');
            $pdf->Cell(25, 0, 'Iva: ', $border, 0, 'C', true);
            $pdf->Cell(25, 0, number_format($sale->iva_amount, 2), $border, 1, 'R');
        }

        // total
        $pdf->SetFont('times', 'B', 11);

        $pdf->Cell(140, 0, '', false, 0, '');
        $pdf->Cell(25, 0, 'Total: ', $border, 0, 'C', true);
        $pdf->Cell(25, 0, number_format($sale->total, 2), $border, 1, 'R');

        // comments
        $pdf->SetY($y);
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(135, 0, 'Comentarios', 1, 1, 'C', true);
        $pdf->MultiCell(135, 20, $sale->comments, true, 'L');

        
        $pdf->Output("vta_$id.pdf");
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $filters = $request->filters;
        $model = new $this->mainModel;

        // set relationships
        if (isset($this->indexJoins) && count($this->indexJoins)) {
            $model = $model->with($this->indexJoins);
        }

        if ($filters) {
            $model = $model->where(function ($query) use ($filters) {
                foreach ($filters as $item) {
                    if (isset($item['isNull'])) {
                        $query = $query->where($item['field'], NULL);
                    } else {
                        $query = $query->where($item['field'], $item['value']);
                    }
                }
            });
        }
        
        if ($search) {
            $model->with('Client')->whereHas('Client', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        if (isset($this->orderBy)) {
            $order = $this->orderBy;
            $model = $model->orderBy($order['field'], $order['type']);
        }

        if ($request->page) {
            $model = $model->paginate($this->indexPaginate);
        } else {
            $model = $model->get();
        }

        return $model;
    }
}
