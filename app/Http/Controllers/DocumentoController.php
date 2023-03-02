<?php

namespace App\Http\Controllers;

use App\Models\Documento;

class DocumentoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(){
        return Documento::all();
    }

    public function show(int $id){
        return Documento::findOrFail($id);
    }

    public function showDetails(int $id){
        
    }

    public function store(){
        dd('hole');
    }

}
