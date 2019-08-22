<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    protected $fillable = [
        'nome', 'email', 'data_nascimento', 'celular', 'logradouro', 'numero', 'bairro', 'cidade', 'estado', 'cep',
        'complemento'
    ];
    protected $table = 'colaboradores';
    public $timestamps = false;

    public function tecnicas()
    {
        return $this->hasMany(Tecnica::class, 'colaborador_id');
    }

    public function comportamentais()
    {
        return $this->hasMany(Comportamento::class, 'colaborador_id');
    }
}
