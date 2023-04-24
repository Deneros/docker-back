<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function index()
    {
        // return User::all();
        return User::join('roles_usuario', 'roles_usuario.id_rol', '=', 'usuario.rol_usuario')
            ->select('*')
            ->get();
    }

    public function show(int $id)
    {
        return User::findOrFail($id);
    }
    // ('documento', 'documento.doc_usuari','=','usuario.usu_id')
    public function showDetails(int $id)
    {

        $documentos = User::join('detalledocumento', 'detalledocumento.det_cordes', '=', 'usuario.usu_email')
            ->join('documento', 'documento.doc_id', '=', 'detalledocumento.det_docume')
            ->select('detalledocumento.*', 'documento.*')
            ->where('usuario.usu_id', '=', $id)
            ->get();

        $counts = $documentos->reduce(function ($carry, $documento) {
            $carry['total_documents']++;

            if ($documento->doc_estado == 'Firmado') {
                $carry['completed_documents']++;
            } elseif ($documento->doc_estado == 'Pendiente') {
                $carry['pending_documents']++;
            }

            return $carry;
        }, ['total_documents' => 0, 'completed_documents' => 0, 'pending_documents' => 0]);

        return response($counts);
        // $total_documents = count($documentos);

        // dd(count($cantidad_firmados));


        // $documentos_firmados = User::join('documento', 'documento.doc_usuari', '=', 'usuario.usu_id')
        //     ->join('detalledocumento', 'documento.doc_id', '=', 'detalledocumento.det_docume')
        //     ->select('*')
        //     ->where('usuario.usu_id', '=', $id)
        //     ->get();

        // var_dump($documentos_firmados);
    }

    public function store()
    {
        dd('hole');
    }
}
