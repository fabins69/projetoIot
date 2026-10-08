<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use App\Models\Sensor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SensorController extends Controller
{
    public function index(Request $request): View
    {
        $termo = trim($request->string('q')->toString());

        $sensores = Sensor::query()
            ->with('ambiente')
            ->withCount('registros')
            ->when($termo !== '', function ($query) use ($termo) {
                $query->where(function ($query) use ($termo) {
                    $query->where('codigo', 'like', "%{$termo}%")
                        ->orWhere('tipo', 'like', "%{$termo}%")
                        ->orWhereHas('ambiente', fn ($ambiente) => $ambiente->where('nome', 'like', "%{$termo}%"));
                });
            })
            ->orderBy('codigo')
            ->paginate(10)
            ->withQueryString();

        return view('sensores.index', compact('sensores', 'termo'));
    }

    public function create(): View
    {
        return view('sensores.create', [
            'sensor' => new Sensor(),
            'ambientes' => Ambiente::orderBy('nome')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'ambiente_id' => ['required', 'integer', 'exists:ambientes,id'],
            'codigo' => ['required', 'string', 'max:80', 'unique:sensors,codigo'],
            'tipo' => ['required', 'string', 'max:40'],
            'descricao' => ['required', 'string', 'max:1000'],
            'status' => ['sometimes', 'boolean'],
        ]);
        $dados['status'] = $request->boolean('status');

        Sensor::create($dados);

        return redirect()->route('sensores.index')->with('success', 'Sensor cadastrado com sucesso.');
    }

    public function edit(Sensor $sensor): View
    {
        return view('sensores.edit', [
            'sensor' => $sensor,
            'ambientes' => Ambiente::orderBy('nome')->get(),
        ]);
    }

    public function update(Request $request, Sensor $sensor): RedirectResponse
    {
        $dados = $request->validate([
            'ambiente_id' => ['required', 'integer', 'exists:ambientes,id'],
            'codigo' => ['required', 'string', 'max:80', Rule::unique('sensors', 'codigo')->ignore($sensor->id)],
            'tipo' => ['required', 'string', 'max:40'],
            'descricao' => ['required', 'string', 'max:1000'],
            'status' => ['sometimes', 'boolean'],
        ]);
        $dados['status'] = $request->boolean('status');

        $sensor->update($dados);

        return redirect()->route('sensores.index')->with('success', 'Sensor atualizado com sucesso.');
    }

    public function destroy(Sensor $sensor): RedirectResponse
    {
        if ($sensor->registros()->exists()) {
            return back()->with('error', 'Este sensor possui leituras salvas. O histórico foi preservado e o sensor não pode ser excluído.');
        }

        $sensor->delete();

        return redirect()->route('sensores.index')->with('success', 'Sensor removido com sucesso.');
    }
}
