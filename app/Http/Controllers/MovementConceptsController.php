<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use App\MovementConcept;

class MovementConceptsController extends BaseController
{
    protected $mainModel = 'App\MovementConcept';

    // params needen for index
    protected $searchFields = ['name'];
    protected $indexPaginate = 10;
    protected $indexJoins = [];
    protected $orderBy = ['field' => 'name', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    // protected $saveFields = ['name','type'];
    protected $storeFields = ['name','type'];
    protected $updateFields = ['name'];
    protected $defaultNulls = ['code'];
    protected $formRules = [
        'name' => 'required',
        'type' => 'required'
    ];

    protected $allowDelete = true;
    protected $allowUpdate = true;
    protected $allowStore  = true;
    protected $except = [];


    public function destroy($id)
    {
    	$mov = MovementConcept::find($id);
    	if ($mov->is_auto) {
    		return Response::json(array('msg' => 'No puede eliminar conceptos automaticos'), 500);
    	} else {
    		parent::destroy($id);
    	}
    }
}
