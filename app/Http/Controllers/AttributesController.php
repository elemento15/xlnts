<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttributesController extends BaseController
{
    protected $mainModel = 'App\Attribute';

    // params needen for index
    protected $searchFields = ['name','description'];
    protected $indexPaginate = 10;
    protected $indexJoins = [];
    protected $orderBy = ['field' => 'display_order', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    protected $saveFields = ['name','min','max','steps','description','display_order'];
    // - protected $storeFields = [];
    // - protected $updateFields = [];
    protected $defaultNulls = [];
    protected $formRules = [
        'name' => 'required',
        'min' => 'required',
        'max' => 'required',
        'steps' => 'required'
    ];

    protected $allowDelete = true;
    protected $allowUpdate = true;
    protected $allowStore  = true;
    protected $except = [];
}