<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentDetail;

class DocumentController extends Controller
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

    public function index()
    { 
        $documentos = DocumentDetail::select(
            'documento.doc_nombre',
            'documento.doc_fechac',
            'documento.doc_horac',
            'documento.doc_fecha_f',
            'documento.doc_hora_f',
            'detalledocumento.det_id',
            'documento.doc_estado',
            'detalledocumento.codigo_verificacion',
            'detalledocumento.det_docume',
            'detalledocumento.det_nomdes',
            'detalledocumento.det_cordes',
            'detalledocumento.det_firma',
            'usuario.usu_id',
            'usuario.usu_nombre',
            'usuario.usu_apelli',
            'usuario.usu_email'
        )
            ->join('documento', 'documento.doc_id', '=', 'detalledocumento.det_docume')
            ->join('usuario', 'usuario.usu_id', '=', 'documento.doc_usuari')
            ->get();

        $agrupados = $documentos->groupBy('det_docume')->map(function ($item, $key) {
            $destinataries = $item->map(function ($detalle) {
                return [
                    'name' => $detalle->det_nomdes,
                    'correo' => $detalle->det_cordes,
                    'signed' => $detalle->det_firma
                ];
            });

            $sender = $item->map(function ($detalle) {
                return [
                    'name' => $detalle->usu_nombre . ' ' . $detalle->usu_apelli,
                    'email' => $detalle->usu_email
                ];
            });

            return [
                'id_document' => $key,
                'id_detail_document' => $item->first()->det_id,
                'document_name' => $item->first()->doc_nombre,
                'send_date' => $item->first()->doc_fechac,
                'send_hour' => $item->first()->doc_horac,
                'sign_date' => $item->first()->doc_fecha_f,
                'sign_hour' => $item->first()->doc_hora_f,
                'state' => $item->first()->doc_estado,
                'verificacion_code' => $item->first()->codigo_verificacion,
                'sender' => $sender->toArray(),
                'destinataries' => $destinataries->toArray()
            ];
        })->values();

        return $agrupados;



        // return ($resultados);
    }

    public function show(int $id)
    {
        return Document::findOrFail($id);
    }

    public function showDetails(int $id)
    {
    }

    public function store()
    {
        dd('hole');
    }
}
