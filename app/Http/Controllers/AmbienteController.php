<?php

namespace App\Http\Controllers;

use App\Models\Ambiente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbienteController extends Controller
{
    public function index(Request $request): View
    {
        $termo = trim($request->string('q')->toString());

        $ambientes = Ambiente::query()
            ->withCount('sensores')
            ->when($termo !== '', fn ($query) => $query->where('nome', 'like', "%{$termo}%"))
            ->orderBy('nome')
            ->paginate(10)
            ->withQueryString();

        return view('ambientes.index', compact('ambientes', 'termo'));
    }

    public function create(): View
    {
        return view('ambientes.create', ['ambiente' => new Ambiente()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'status' => ['sometimes', 'boolean'],
        ]);
        $dados['status'] = $request->boolean('status');

        Ambiente::create($dados);

        return redirect()->route('ambientes.index')->with('success', 'Ambiente cadastrado com sucesso.');
    }

    public function edit(Ambiente $ambiente): View
    {
        return view('ambientes.edit', compact('ambiente'));
    }

    public function update(Request $request, Ambiente $ambiente): RedirectResponse
    {
        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:120'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'status' => ['sometimes', 'boolean'],
        ]);
        $dados['status'] = $request->boolean('status');

        $ambiente->update($dados);

        return redirect()->route('ambientes.index')->with('success', 'Ambiente atualizado com sucesso.');
    }

    public function destroy(Ambiente $ambiente): RedirectResponse
    {
        if ($ambiente->sensores()->exists()) {
            return back()->with('error', 'Este ambiente possui sensores vinculados. Remova ou mova os sensores antes de excluí-lo.');
        }

        $ambiente->delete();

        return redirect()->route('ambientes.index')->with('success', 'Ambiente removido com sucesso.');
    }
}
