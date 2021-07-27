<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    public function editor() {
        return $this->belongsTo('App\User');
    }
}
