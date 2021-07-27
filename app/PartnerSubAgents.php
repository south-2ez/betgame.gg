<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerSubAgents extends Model
{
    use SoftDeletes;

    protected $table = 'partner_subagents';
    protected $fillable = ['partner_id', 'sub_partner_id'];

    public function partner()
    {
        return $this->belongsTo('\App\Partner', 'partner_id');
    }
}
