<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\SaleProduct;
use App\VisitType;
use App\Visit;
use App\Movement;
use App\MovementConcept;
use App\MovementProduct;

class SalesController extends BaseController
{
    protected $mainModel = 'App\Sale';

    // params needen for index
    protected $searchFields = [];
    protected $indexPaginate = 10;
    protected $indexJoins = ['products'];
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
                Visit::create([
                    'visit_date' => date('Y-m-d H:i:s'),
                    'visit_type_id' => $type->id,
                    'client_id' => $request->client_id
                ]);

                
                // =============== Create the Sale ===============
                $subtotal = 0;
                
                $sale = $mainModel::create([
                    'sale_date' => date('Y-m-d H:i:s'),
                    'client_id' => $request->client_id,
                    'has_invoice' => $request->has_invoice,
                    'iva_percent' => ($request->has_invoice) ? 16 : 0, // TODO: get iva_percent from configurations
                    'comments' => $request->comments
                ]);

                // save products
                foreach ($request->products as $item) {
                    $product = Product::find($item['id']);

                    $sale_product = SaleProduct::create([
                        'sale_id'       => $sale->id,
                        'product_id'    => $product->id,
                        'product_type'  => $product->type,
                        'description'   => $product->description,
                        'quantity'      => $item['quantity'],
                        'price'         => $product->price,
                        'subtotal'      => $item['quantity'] * $product->price,
                        'is_devolution' => 0,
                    ]);

                    $subtotal += $sale_product->subtotal;
                }

                // set calculated fields for sales
                $sale->subtotal = $subtotal;
                $sale->iva_amount = $subtotal * ($sale->iva_percent / 100); 
                $sale->total = $subtotal + $sale->iva_amount;
                $sale->save();


                // =============== Create the Movement ===============
                $concept = MovementConcept::findByCode('VTA');

                $movement = Movement::create([
                    'mov_date' => date('Y-m-d H:i:s'),
                    'movement_concept_id' => $concept->id,
                    'type' => $concept->type,
                    'active' => 1
                ]);

                // save movement's products
                foreach ($request->products as $item) {
                    $movProd = MovementProduct::create([
                        'movement_id' => $movement->id,
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity']
                    ]);
                }

                // update stock
                $movement->updateStock();


                DB::commit();

                return $sale;
            } catch (Exception $e) {
                return Response::json(array('msg' => 'Error al guardar'), 500);
            }
        }
    }
}
