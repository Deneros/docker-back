<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DocumentDetail;
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
        return User::join('roles_usuario', 'roles_usuario.id_rol', '=', 'usuario.rol_usuario')
            ->select('*')
            ->get();
    }

    public function show(int $id)
    {
        return User::findOrFail($id);
    }

    public function showAllUserDetails()
    {
        $documentos = User::join('detalledocumento', 'detalledocumento.det_cordes', '=', 'usuario.usu_email')
            ->join('documento', 'documento.doc_id', '=', 'detalledocumento.det_docume')
            ->select('detalledocumento.*', 'documento.*', 'usuario.*')
            ->get();

        $documentosAgrupados = $documentos->groupBy('usu_id');

        $firmadosPorUsuario = $documentosAgrupados->map(function ($documentosUsuario, $userId) {
            $cantidadFirmados = $documentosUsuario->reduce(function ($carry, $documento) {
                return $carry + ($documento->doc_estado == 'Firmado' ? 1 : 0);
            }, 0);

            $nombreUsuario = $documentosUsuario->first()->usu_nombre.' '.$documentosUsuario->first()->usu_apelli; // Obtén el nombre del usuario

            return [
                'user_name' => $nombreUsuario,
                'signed_documents_count' => $cantidadFirmados,
            ];
        });

        return response($firmadosPorUsuario->values());
    }

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
    }

    public function userDocuments(int $id, String $state)
    {
        $states = [
            'completed' => 'Firmado',
            'pending' => 'Pendiente',
            'returned' => 'Devuelto'
        ];

        $user = User::findOrFail($id);

        $filteredDocuments = DocumentDetail::select('detalledocumento.det_docume', 'documento.doc_estado')
            ->join('documento', 'documento.doc_id', '=', 'detalledocumento.det_docume')
            ->join('usuario', 'usuario.usu_id', '=', 'documento.doc_usuari')
            ->where('detalledocumento.det_cordes', '=', $user->usu_email)
            ->where('documento.doc_estado', '=', $states[$state])
            ->get();

        $filteredDocumentIds = $filteredDocuments->pluck('det_docume')->unique();

        $documents = DocumentDetail::select(
            'documento.doc_nombre',
            'documento.doc_fechac',
            'documento.doc_horac',
            'documento.doc_fecha_f',
            'documento.doc_hora_f',
            'detalledocumento.det_id',
            'documento.doc_estado',
            'detalledocumento.det_docume',
            'detalledocumento.det_nomdes',
            'detalledocumento.det_cordes',
            'detalledocumento.det_firma',
        )
            ->join('documento', 'documento.doc_id', '=', 'detalledocumento.det_docume')
            ->join('usuario', 'usuario.usu_id', '=', 'documento.doc_usuari')
            ->whereIn('detalledocumento.det_docume', $filteredDocumentIds)
            ->get();

        $group = $documents->groupBy('det_docume')->map(function ($item, $key) {
            $destinataries = $item->pluck('det_cordes')->unique()->implode(', ');

            $firstItem = $item->first();
            $otherFields = [
                'id_document' => $key,
                'id_detail_document' => $firstItem->det_id,
                'document_name' => $firstItem->doc_nombre,
                'send_date' => $firstItem->doc_fechac,
                'send_hour' => $firstItem->doc_horac,
                'sign_date' => $firstItem->doc_fecha_f,
                'sign_hour' => $firstItem->doc_hora_f,
                'state' => $firstItem->doc_estado,

            ];
            return array_merge($otherFields, ['destinataries' => $destinataries]);
        })->values();

        return $group;
    }

    public function store()
    {
        dd('hole');
    }
}
