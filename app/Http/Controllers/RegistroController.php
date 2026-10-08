<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistroController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'cod_sensor' => ['required', 'string', Rule::exists('sensors', 'codigo')],
            'valor' => ['required', 'numeric'],
            'umidade' => ['required', 'numeric', 'between:0,100'],
        ]);

        $sensor = Sensor::where('codigo', $dados['cod_sensor'])->firstOrFail();
        $registro = $sensor->registros()->create([
            'valor' => $dados['valor'],
            'umidade' => $dados['umidade'],
            'data_hora' => now(),
        ]);

        return response()->json([
            'success' => 'Cadastrado',
            'data' => $registro,
        ], 201);
    }

    public function getValor(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'cod_sensor' => ['required', 'string', Rule::exists('sensors', 'codigo')],
        ]);

        $sensor = Sensor::where('codigo', $dados['cod_sensor'])->firstOrFail();
        $registro = $sensor->registros()->latest('data_hora')->first();

        if (! $registro) {
            return response()->json(['error' => 'Nenhuma leitura encontrada para este sensor.'], 404);
        }

        return response()->json(['valor' => $registro->valor]);
    }
}
