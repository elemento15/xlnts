<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\MovementProduct;
use App\MovementConcept;

class MovementsController extends BaseController
{
    protected $mainModel = 'App\Movement';

    // params needen for index
    protected $searchFields = ['id'];
    protected $indexPaginate = 10;
    protected $indexJoins = ['movement_concept'];
    protected $orderBy = ['field' => 'mov_date', 'type' => 'DESC'];
    
    // params needer for show
    protected $showJoins = ['movement_concept', 'products', 'products.product', 'products.product.group'];

    // params needed for store/update
    protected $saveFields = ['movement_concept_id'];
    // - protected $storeFields = ['name','type'];
    // - protected $updateFields = ['name'];
    protected $defaultNulls = [];
    protected $formRules = [
        'movement_concept_id' => 'required'
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
                // save movement
                $movement = $mainModel::create([
                    'mov_date' => date('Y-m-d H:i:s'),
                    'movement_concept_id' => $request->movement_concept_id,
                    'type' => $request->type,
                    'active' => 1,
                    'comments' => $request->comments
                ]);

                // save products
                foreach ($request->products as $key => $product) {
                    $movProd = MovementProduct::create([
                        'movement_id' => $movement->id,
                        'product_id' => $product['id'],
                        'quantity' => $product['quantity']
                    ]);
                }

                // update stock
                $movement->updateStock();

                return $movement;
            } catch (Exception $e) {
                return Response::json(array('msg' => 'Error al guardar'), 500);
            }
        }
    }

    /**
     * Cancel a resource in storage.
     *
     * @return Response
     */
    public function cancel($id)
    {
        $mainModel = $this->mainModel;

        $record = $mainModel::find($id);

        if (! $record) {
            return Response::json(array('msg' => 'Registro no encontrado'), 500);
        }

        if (! $record->active) {
            return Response::json(array('msg' => 'Movimiento ya esta cancelado'), 500);
        }

        if ($record->movement_concept->is_auto) {
            return Response::json(array('msg' => 'Movimiento generado automaticamente'), 500);
        }

        DB::beginTransaction();

        $record->active = 0;
        $record->cancel_date = date('Y-m-d H:i:s');
        
        if ($record->save()) {
            $record->reverseStock();

            DB::commit();

            return Response::json($record);
        } else {
            return Response::json(array('msg' => 'Error al cancelar'), 500);
        }
    }
}
