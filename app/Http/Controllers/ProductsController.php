<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends BaseController
{
    protected $mainModel = 'App\Product';

    // params needen for index
    protected $searchFields = ['description'];
    protected $indexPaginate = 10;
    protected $indexJoins = ['group'];
    protected $orderBy = ['field' => 'description', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = ['group'];

    // params needed for store/update
    // protected $saveFields = ['description','type','group_id','price','has_attributes','comments'];
    protected $storeFields = ['description','type','group_id','price','has_attributes','comments'];
    protected $updateFields = ['description','group_id','price','has_attributes','comments'];
    protected $defaultNulls = ['comments'];
    protected $formRules = [
        'description' => 'required',
        'group_id' => 'required',
    ];

    protected $allowDelete = true;
    protected $allowUpdate = true;
    protected $allowStore  = true;
    protected $except = [];
}