<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransactionNote extends Model
{
	public function transaction()
	{
		return $this->belongsTo('App\Transaction');
	}
}
