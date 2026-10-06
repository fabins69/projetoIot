<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ambiente_id',
        'codigo', // TEMP01, TEMP02, LED01, LED02
        'tipo', // LED, TEMPERATURA
        'descricao',
        'status' // ativo ou inativo
    ];

    public function registros(){
        return $this->hasMany(Registro::class);
    }

    public function ambientes(){
        return $this->hasMany(Ambiente::class);
    }
}
