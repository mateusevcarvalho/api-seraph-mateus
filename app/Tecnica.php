<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tecnica extends Model
{
    protected $fillable = ['nome'];
    public $timestamps = false;
}
