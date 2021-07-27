<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;

class MarketController extends Controller
{
    public function userPurchase(Request $request)
    {
    	$rules = array(
    	    'i_buyer'      => 'required',
    	    'i_province'   => 'required',
    	    'i_city'       => 'required',
    	    'i_street'     => 'required',
    	    'i_itemname'   => 'required',
    	    'i_price'      => 'required',
    	    'i_quantity'   => 'required|numeric',
    	    'i_contact'	   => 'required|numeric',
    	);

    	$validator = \Validator::make($request->all(), $rules);  
    	if($validator->passes()) {
    	    $purchase = new Purchase;

    	    $purchase->name       = $request->i_buyer;
    	    $purchase->address    = $request->i_street.','.$request->i_city.' '.$request->i_province;
    	    $purchase->item_name  = $request->i_itemname;
    	    $purchase->contact    = $request->i_contact;
    	    $purchase->price      = $request->i_price;
    	    $purchase->quantity   = $request->i_quantity;
    	    $purchase->save();
    	    $return['success'] = false;
    	}else{
    	    $return['errors'] = $validator->errors();
    	    return $return;
    	}
    }
}
