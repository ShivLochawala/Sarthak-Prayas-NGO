<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $hidden = [
        '_token',
        '_method',
    ];

    public $timestamps = false;

    public function beneficiaries(){
        return $this->belongsToMany('App\Models\Beneficiarie','beneficiarie_sponsors','sponsor_id','beneficiarie_id');
    }

}
