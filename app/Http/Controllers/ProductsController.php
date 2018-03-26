<?php

namespace App\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use App\Product;
use App\AttributeProduct;

class ProductsController extends BaseController
{
    protected $mainModel = 'App\Product';

    // params needen for index
    protected $searchFields = ['description'];
    protected $indexPaginate = 10;
    protected $indexJoins = ['group','attributes'];
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

    public function saveAttributes($id, Request $request)
    {
        $product = Product::find($id);
        $checked = 0; // count how many attributes are checked

        // save attributes related to product
        foreach ($request['attributes'] as $item) {
            $product->attributes()->updateOrCreate(
                ['attribute_id' => $item['attribute_id']],
                ['checked' => $item['checked']]
            );

            $checked += ($item['checked']) ? 1 : 0;
        }

        // update product's 'has_attributes'
        $product->has_attributes = ($checked) ? true : false;
        $product->save(); 

        return Response::json($product);
    }
}