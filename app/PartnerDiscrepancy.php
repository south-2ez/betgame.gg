<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartnerDiscrepancy extends Model
{
    public function transaction()
	{
		return $this->belongsTo('App\PartnerTransaction');
	}
	public function processBy()
	{
		return $this->belongsTo('App\User','user_id');
	}
}
