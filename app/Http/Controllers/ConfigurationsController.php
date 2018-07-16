<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;

use App\Configuration;

class ConfigurationsController extends BaseController
{
    protected $mainModel = 'App\Configuration';

    // params needen for index
    protected $searchFields = [];
    protected $indexPaginate = 10;
    protected $indexJoins = [];
    protected $orderBy = ['field' => 'id', 'type' => 'ASC'];
    
    // params needer for show
    protected $showJoins = [];

    // params needed for store/update
    protected $saveFields = ['iva'];
    // - protected $storeFields = [];
    // - protected $updateFields = [];
    protected $defaultNulls = [];
    protected $formRules = [];

    protected $allowDelete = false;
    protected $allowUpdate = false;
    protected $allowStore  = false;
    protected $except = [];


    public function save(Request $request)
    {
        $errors = [];
        
        // custom validations
        if (! $request->iva || $request->iva <= 0 || $request->iva > 30) {
            $errors[] = 'IVA inválido';
        }

        if (count($errors)) {
            return Response::json(array('errors' => $errors), 422);
        }

        // only one record in table
        if (! $config = Configuration::first()) {
            $config = new Configuration();
        }

        $config->iva = $request->iva;
        $config->save();

        return Response::json($config);
        
    }

    public function read()
    {
        if ($config = Configuration::first()) {
            return $config;
        } else {
            return Response::json(array('msg' => 'Error al leer configuraciones'), 500);
        }

    }
}