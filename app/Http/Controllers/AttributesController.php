<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttributesController extends BaseController
{
    protected $mainModel = 'App\Attribute';

    // params needen for index
    protected $searchFields = ['name'];
    protected $indexPaginate = 10;
    protected $indexJoins = [];
    protected $orderBy = ['field' => 'name', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    protected $saveFields = ['name','min','max'];
    // - protected $storeFields = [];
    // - protected $updateFields = [];
    protected $defaultNulls = [];
    protected $formRules = [
        'name' => 'required'
    ];

    protected $allowDelete = true;
    protected $allowUpdate = true;
    protected $allowStore  = true;
    protected $except = [];
}