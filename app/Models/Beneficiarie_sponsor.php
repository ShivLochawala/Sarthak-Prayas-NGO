<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiarie_sponsor extends Model
{
    use HasFactory;

    protected $fillable = ['beneficiarie_id','sponsor_id','flag'];
    public $timestamps = false;
}
