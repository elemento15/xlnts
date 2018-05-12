<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}