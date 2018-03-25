<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RolesController extends BaseController
{
    protected $mainModel = 'App\Role';

    // params needen for index
    protected $searchFields = ['name','code'];
    protected $indexPaginate = 10;
    protected $indexJoins = [];
    protected $orderBy = ['field' => 'name', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    // - protected $saveFields = ['name','email','role_id'];
    // - protected $storeFields = [];
    // - protected $updateFields = [];
    protected $defaultNulls = [];
    protected $formRules = [];

    protected $allowDelete = false;
    protected $allowUpdate = false;
    protected $allowStore  = false;
    protected $except = [];
}
