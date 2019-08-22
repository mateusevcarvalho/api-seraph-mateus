<?php

namespace App\Http\Controllers;

use App\Colaborador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColaboradoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Colaborador $colaborador)
    {
        $request = $request->all();
        if (count($request)) {
            $response = $colaborador->with(['tecnicas', 'comportamentais'])
                ->where(function ($query) use ($request) {
                    if (isset($request['nome']) && $request['nome']) {
                        $query->where('nome', 'like', "%{$request['nome']}%");
                    }
                    if (isset($request['email']) && $request['email']) {
                        $query->where('email', 'like', "%{$request['email']}%");
                    }
                    if (isset($request['celular']) && $request['celular']) {
                        $query->where('celular', 'like', "%{$request['celular']}%");
                    }
                    if (isset($request['data_nascimento']) && $request['data_nascimento']) {
                        $query->where('data_nascimento', $request['data_nascimento']);
                    }
                })->get();
        } else {
            $response = $colaborador->with(['tecnicas', 'comportamentais'])->get();
        }
        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        $colaborador = Colaborador::create($request->all());
        if ($colaborador) {
            $tecnicas = $colaborador->tecnicas()->createMany($request->get('tecnicas'));
            $comportamentais = $colaborador->comportamentais()->createMany($request->get('comportamentais'));
            if ($tecnicas && $comportamentais) {
                DB::commit();
                return response()->json('Colaborador criado com sucesso!');
            } else {
                DB::rollBack();
                return response()->json('Falha na criação do colaborador!', 500);
            }
        }
        return response()->json('Falha na criação do colaborador!', 500);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $colaborador = Colaborador::with(['tecnicas', 'comportamentais'])->findOrFail($id);
        return response()->json($colaborador);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        $colaborador = Colaborador::findOrFail($id);
        if ($colaborador->update($request->all())) {
            $colaborador->tecnicas()->delete();
            $colaborador->comportamentais()->delete();
            $tecnicas = $colaborador->tecnicas()->createMany($request->get('tecnicas'));
            $comportamentais = $colaborador->comportamentais()->createMany($request->get('comportamentais'));
            if ($tecnicas && $comportamentais) {
                DB::commit();
                return response()->json('Colaborador editado com sucesso!');
            } else {
                DB::rollBack();
                return response()->json('Falha na edição do colaborador!', 500);
            }
        }
        return response()->json('Falha na edição do colaborador!', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $colaborador = Colaborador::findOrFail($id);
        if ($colaborador->delete()) {
            return response()->json('Colaborador apagado com sucesso!');
        }
        return response()->json('Falha ao apagar o colaborador', 500);
    }
}
