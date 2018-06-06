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
    protected $indexJoins = ['visit_type'];
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

                // get visit type
                $type = VisitType::findByCode($request->type);

                // save visit
                $visit = $mainModel::create([
                    'visit_date' => date('Y-m-d h:i:s'),
                    'visit_type_id' => $type->id,
                    'client_id' => $request->client_id
                ]);

                // save attributes
                foreach ($request->visitAttributes as $attr) {
                    VisitAttribute::create([
                        'visit_id'     => $visit->id,
                        'type'         => 'L',
                        'attribute_id' => $attr['id'],
                        'value'       => $attr['value']
                    ]);
                }

                DB::commit();

                return $visit;
            } catch (Exception $e) {
                return Response::json(array('msg' => 'Error al guardar'), 500);
            }
        }
    }
}
