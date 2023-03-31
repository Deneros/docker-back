<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\DetalleDocumento;

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

    public function index()
    {
        $documentos = DetalleDocumento::select(
            'documento.doc_nombre',
            'documento.doc_fechac',
            'documento.doc_horac',
            'detalledocumento.det_id',
            'documento.doc_estado',
            'detalledocumento.codigo_verificacion',
            'detalledocumento.det_docume',
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
            $det_cordes = $item->map(function ($detalle) {
                return [
                    'correo' => $detalle->det_cordes,
                    'firmado' => $detalle->det_firma
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
                'state' => $item->first()->doc_estado,
                'verificacion_code' => $item->first()->codigo_verificacion,
                'sender' => $sender->toArray(),
                'destinataries' => $det_cordes->toArray()
            ];
        })->values();

        return $agrupados;



        // return ($resultados);
    }

    public function show(int $id)
    {
        return Documento::findOrFail($id);
    }

    public function showDetails(int $id)
    {
    }

    public function store()
    {
        dd('hole');
    }
}
