<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\Sensor;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    public function store(Request $request){
        $sensor = Sensor::where('codigo', $request->cod_sensor)->first();

        if(!$sensor){
            return response()->json(['error'=> 'sensor não encontrado']);
        }

        $registro = Registro::create([
            'sensor_id' => $sensor->id,
            'valor' => $request->valor,
            'umidade' => $request->umidade,
            'data_hora' => now()
        ]);

        return response()->json([
            'success' => 'Cadastrado',
            'data' => $registro
        ]);
    }

    public function getValor(Request $request){
        $sensor = Sensor::where('codigo', $request->cod_sensor)->fisrt();

        $valor = Registro::where('sensor_id', $sensor->id)->last();

        return response()->json(['valor' =>$valor->valor]);
    }
}
