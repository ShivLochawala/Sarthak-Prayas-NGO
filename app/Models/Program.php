<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $primaryKey = "id";

    protected $fillable = [
        'name',
        'desc',
        'levels',
        'amount',
        'frequency',
        'isactive',
    ];

    public $timestamps = false;

    protected $hidden = [
        'count',
    ];


}
