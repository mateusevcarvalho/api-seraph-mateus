<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comportamento extends Model
{
    protected $table = 'comportamentais';
    protected $fillable = ['nome'];
    public $timestamps = false;
}
