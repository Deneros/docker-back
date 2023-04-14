<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\DocumentDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // $documentos = DocumentDetail::select(
        //     'documento.doc_nombre',
        //     'documento.doc_fechac',
        //     'documento.doc_horac',
        //     'documento.doc_fecha_f',
        //     'documento.doc_hora_f',
        //     'detalledocumento.det_id',
        //     'documento.doc_estado',
        //     'detalledocumento.codigo_verificacion',
        //     'detalledocumento.det_docume',
        //     'detalledocumento.det_nomdes',
        //     'detalledocumento.det_cordes',
        //     'detalledocumento.det_firma',
        //     'usuario.usu_id',
        //     'usuario.usu_nombre',
        //     'usuario.usu_apelli',
        //     'usuario.usu_email'
        // )
        //     ->join('documento', 'documento.doc_id', '=', 'detalledocumento.det_docume')
        //     ->join('usuario', 'usuario.usu_id', '=', 'documento.doc_usuari')
        //     ->where('doc_estado','=','Firmado')
        //     ->get();

        // $agrupados = $documentos->groupBy('det_docume')->map(function ($item, $key) {
        //     $destinataries = $item->map(function ($detalle) {
        //         return [
        //             'name' => $detalle->det_nomdes,
        //             'correo' => $detalle->det_cordes,
        //             'signed' => $detalle->det_firma
        //         ];
        //     });

        //     $sender = $item->map(function ($detalle) {
        //         return [
        //             'name' => $detalle->usu_nombre . ' ' . $detalle->usu_apelli,
        //             'email' => $detalle->usu_email
        //         ];
        //     });

        //     return [
        //         'id_document' => $key,
        //         'id_detail_document' => $item->first()->det_id,
        //         'document_name' => $item->first()->doc_nombre,
        //         'send_date' => $item->first()->doc_fechac,
        //         'send_hour' => $item->first()->doc_horac,
        //         'sign_date' => $item->first()->doc_fecha_f,
        //         'sign_hour' => $item->first()->doc_hora_f,
        //         'state' => $item->first()->doc_estado,
        //         'verificacion_code' => $item->first()->codigo_verificacion,
        //         'sender' => $sender->toArray(),
        //         'destinataries' => $destinataries->toArray()
        //     ];
        // })->values();

        $products = Product::All();

        $bought_firms = 0;
        $used_firms = 0;

        foreach ($products as $product) {
            $bought_firms += $product->total_firmas;
            $used_firms += $product->cantidad_firmas;
        }

        $response = [
            [
                'bought_firms' => $bought_firms,
                'used_firms' => $used_firms,
            ]
        ];

        return response()->json($response);
    }
}
