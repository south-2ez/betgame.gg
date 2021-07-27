<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $hidden = [
        'partner_credits',
        'partner_earnings',
    ];

    public function userOwner(){
        return $this->belongsTo('\App\User', 'user_id');
    }
    
    public function agentTransactions()
    {
        return $this->hasMany('App\PartnerTransaction', 'partner_id');
    }

    public function province()
    {
        return $this->hasOne('\App\Province', 'id', 'province_id');
    }

    public function processBy()
    {
        return $this->belongsTo('App\User', 'process_by');
    }

    public function profile()
    {
        return $this->hasOne('\App\PartnerProfile', 'partner_id');
    }

    public function affliates()
    {
        return $this->hasMany('\App\PartnersJoint', 'partner_id');
    }

    public function subUsers()
    {
        return $this->hasMany('\App\PartnerSubUsers', 'partner_id');
    }    

    public function subAgents()
    {
        return $this->hasMany('\App\PartnerSubAgents', 'partner_id');
    }

    public function parentPartner()
    {
        return $this->hasOne('\App\PartnerSubAgents', 'sub_partner_id');
    }

    public function mop()
    {
        return $this->hasMany('\App\PartnersMop', 'partner_id');
    }
}
