<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends BaseController
{
    protected $mainModel = 'App\User';

    // params needen for index
    protected $searchFields = ['name','email'];
    protected $indexPaginate = 10;
    protected $indexJoins = ['role'];
    protected $orderBy = ['field' => 'name', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = ['role'];

    // params needed for store/update
    protected $saveFields = ['name','email','role_id'];
    // - protected $storeFields = [];
    // - protected $updateFields = [];
    protected $defaultNulls = ['role_id'];
    protected $formRules = [
        'name' => 'required',
        'email' => 'required',
        'role_id' => 'required',
    ];

    protected $allowDelete = false;
    protected $allowUpdate = true;
    protected $allowStore  = true;
    protected $except = [];
}
