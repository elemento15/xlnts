<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\VisitType;
use App\VisitAttribute;

class VisitsController extends BaseController
{
    protected $mainModel = 'App\Visit';

    // params needen for index
    protected $searchFields = [];
    protected $indexPaginate = 10;
    protected $indexJoins = ['visit_type','visit_attributes', 'visit_attributes.attribute'];
    protected $orderBy = ['field' => 'visit_date', 'type' => 'DESC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    // protected $saveFields = ['name','type'];
    protected $storeFields = ['visit_date','visit_type_id', 'client_id'];
    protected $updateFields = [];
    protected $defaultNulls = [];
    protected $formRules = [
        'type' => 'required',
        'client_id' => 'required'
    ];

    protected $allowDelete = false;
    protected $allowUpdate = true;
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

                // get visit type
                $type = VisitType::findByCode($request->type);

                // save visit
                $visit = $mainModel::create([
                    'visit_date' => date('Y-m-d h:i:s'),
                    'visit_type_id' => $type->id,
                    'client_id' => $request->client_id
                ]);

                // save attributes
                foreach ($request->visit_attributes as $attr) {
                    VisitAttribute::create([
                        'visit_id'     => $visit->id,
                        'type'         => 'L',
                        'attribute_id' => $attr['attribute_id'],
                        'value'        => $attr['value']
                    ]);
                }

                DB::commit();

                return $visit;
            } catch (Exception $e) {
                return Response::json(array('msg' => 'Error al guardar'), 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
        if (! $this->allowUpdate) {
            return Response::json(array('msg' => 'Modelo no permite actualizar'), 500);
        }

        $mainModel = $this->mainModel;

        if (method_exists($this, 'beforeUpdate')) {
            if (! $this->beforeStore($request)) {
                return Response::json(array('msg' => $this->msgError), 500);
            }
        }
        
        foreach ($this->defaultNulls as $item) {
            $request[$item] = ($request[$item] == '') ? null : $request[$item];
        }

        $rules = $this->parseFormRules($id);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(array('errors' => $validator->errors()->all()), 422);
        } else {
            $visit = $mainModel::find($id);

            if (! $visit) {
                return Response::json(array('msg' => 'Registro no encontrado'), 500);
            }

            try {
                DB::beginTransaction();

                $visit->touch(); // updated_at

                // save attributes
                foreach ($request->visit_attributes as $attr) {
                    if ($attr['id']) {
                        $rec = VisitAttribute::find($attr['id']);
                        $rec->value = $attr['value'];
                        $rec->save();
                    } else {
                        VisitAttribute::create([
                            'visit_id'     => $visit->id,
                            'type'         => 'L',
                            'attribute_id' => $attr['attribute_id'],
                            'value'        => $attr['value']
                        ]);
                    }
                }

                DB::commit();

            } catch (Exception $e) {
                return Response::json(array('msg' => 'Error al guardar'), 500);
            }
        
            return $visit;
        }
    }
}
