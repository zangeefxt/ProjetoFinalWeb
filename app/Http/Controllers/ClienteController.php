<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Endereco;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ClienteController extends Controller
{

    public function index(Request $request)
    {
        $query = Cliente::query();

        
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('cpf', 'LIKE', "%$search%")
                ->orWhere('telefone', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
        }

        $clientes = $query->get();

        return view('clientes.index', compact('clientes'));
    }




    public function create()
    {
        return view('clientes.create');
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:15',
            'cpf' => 'required|string|max:14|unique:clientes,cpf',
            'email' => 'required|email|unique:clientes,email',
            'cidade' => 'required|string|max:255',
            'cep' => 'required|string|size:8',
            'rua' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'bairro' => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
        ]);


        $endereco = Endereco::create([
            'cidade' => $validated['cidade'],
            'cep' => $validated['cep'],
            'rua' => $validated['rua'],
            'numero' => $validated['numero'],
            'bairro' => $validated['bairro'],
            'complemento' => $validated['complemento'] ?? null,
        ]);


        Cliente::create([
            'nome' => $validated['nome'],
            'telefone' => $validated['telefone'],
            'cpf' => $validated['cpf'],
            'email' => $validated['email'],
            'endereco_id' => $endereco->id,
        ]);


        return redirect()->route('clientes.index')->with('success', 'Cliente cadastrado com sucesso!');
    }



    public function show($id)
    {
        $cliente = Cliente::with('endereco')->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }


    public function edit($id)
    {
        $cliente = Cliente::with('endereco')->findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }


    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:15',
            'cpf' => 'required|string|size:11|unique:clientes,cpf,' . $id,
            'email' => 'required|email|unique:clientes,email,' . $id,
            'cidade' => 'required|string|max:255',
            'cep' => 'required|string|size:8',
            'rua' => 'required|string|max:255',
            'numero' => 'required|string|max:10',
            'bairro' => 'required|string|max:255',
            'complemento' => 'nullable|string|max:255',
        ]);

        $cliente = Cliente::findOrFail($id);
        $endereco = $cliente->endereco;


        $endereco->update([
            'cidade' => $validated['cidade'],
            'cep' => $validated['cep'],
            'rua' => $validated['rua'],
            'numero' => $validated['numero'],
            'bairro' => $validated['bairro'],
            'complemento' => $validated['complemento'],
        ]);


        $cliente->update([
            'nome' => $validated['nome'],
            'telefone' => $validated['telefone'],
            'cpf' => $validated['cpf'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente excluído com sucesso!');
    }
}

