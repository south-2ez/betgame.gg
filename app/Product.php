<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // protected $fillable = ['product_name', 'product_price', 'product_desc', 'product_image'];

    /**
     *  Get the user that Purchase
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}