<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\VisitType;
use App\Visit;
use App\VisitAttribute;

class ClientsController extends BaseController
{
    protected $mainModel = 'App\Client';

    // params needen for index
    protected $searchFields = ['name','rfc','email','phone','mobile'];
    protected $indexPaginate = 10;
    protected $indexJoins = [];
    protected $orderBy = ['field' => 'name', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    protected $saveFields = ['name','rfc','email','phone','mobile','birthday','comments'];
    // - protected $storeFields = [];
    // - protected $updateFields = [];
    protected $defaultNulls = ['rfc','email','phone','mobile','birthday','comments'];
    protected $formRules = [
        'name' => 'required'
    ];

    protected $allowDelete = true;
    protected $allowUpdate = true;
    protected $allowStore  = true;
    protected $except = [];


    /*public function saveVisit($id, Request $request)
    {
        // get the visit type for "Visit"
        $type = VisitType::findByCode('VIS');

        DB::beginTransaction();

        $visit = Visit::create([
            'visit_date' => date('Y-m-d H:i:s'),
            'visit_type_id' => $type->id,
            'client_id' => $id
        ]);


        // save attributes values
        foreach ($request->visitAttributes as $attr) {
            VisitAttribute::create([
                'visit_id'     => $visit->id,
                'type'         => 'L',
                'attribute_id' => $attr['id'],
                'value'       => $attr['value']
            ]);
        }

        DB::commit();

        return Response::json($visit);
    }*/
}