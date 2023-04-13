<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index(){
        return User::all();
    }

    public function show(int $id){
        return User::findOrFail($id);
    }

    public function showDetails(int $id){

        $documentos = User::join('documento', 'documento.doc_usuari','=','usuario.usu_id')
        ->select('*')
        ->where('usuario.usu_id' ,'=', $id)
        ->get();

        $cantidad_enviados = count($documentos);
        $cantidad_firmados = Arr::where($documentos->toArray(), function($value){
            return ($value['doc_estado']=='Firmado');
        });

        dd(count($cantidad_firmados));


        $documentos_firmados = User::join('documento', 'documento.doc_usuari','=','usuario.usu_id')
        ->join('detalledocumento','documento.doc_id','=','detalledocumento.det_docume')
        ->select('*')
        ->where('usuario.usu_id' ,'=', $id)
        ->get();
        
        // var_dump($documentos_firmados);
    }

    public function store(){
        dd('hole');
    }

}
