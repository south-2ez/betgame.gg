<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionDiscrepancy extends Model
{
	public function transaction()
	{
		return $this->belongsTo('App\Transaction');
	}

	public function processBy()
	{
		return $this->belongsTo('App\User','user_id');
	}
}
