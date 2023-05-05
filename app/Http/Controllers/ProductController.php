<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Product;
use App\Models\DocumentDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::All();
        $documents =  Document::where('doc_estado','=','Firmado')->get();

        $bought_firms = 0;
        $used_firms = 0;

        // return $documents;

        foreach ($products as $product) {
            $bought_firms += $product->total_firmas;
            $used_firms += $product->cantidad_firmas;
        }

        $response = [
            'bought_firms' => $bought_firms,
            'used_firms' => $used_firms,
            'total_documents'=>count($documents)
        ];

        return response()->json($response);
    }

    public function productReports()
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
        $senderGroups = $documentos->groupBy('usu_id');
        $sendersCount = collect();

        foreach ($senderGroups as $senderId => $senderDocuments) {
            $senderInfo = [
                'name' => $senderDocuments->first()->usu_nombre . ' ' . $senderDocuments->first()->usu_apelli,
                'email' => $senderDocuments->first()->usu_email
            ];

            $sendCount = $senderDocuments->count();


            $sendersCount->push([
                'sender' => $senderInfo,
                'send_count' => $sendCount
            ]);
        }

        $sendersCount = $sendersCount->sortByDesc('send_count');

        $topSenders = $sendersCount->take(5)->values()->all(); // Top 5 remitentes, asegúrese de resetear los índices y convertirlo en un array

        $monthlyCounts = $documentos->groupBy(function ($documento) {
            return Carbon::parse($documento->doc_fechac)->format('Y-m');
        })->map(function ($groupedDocs) {
            return $groupedDocs->count();
        });

        $monthlyCounts = $monthlyCounts->sortDesc();

        $signedDocumentos = $documentos->filter(function ($documento) {
            return $documento->doc_estado == 'Firmado';
        });

        $signedSenderGroups = $signedDocumentos->groupBy('usu_id');
        $signedSendersCount = collect();

        foreach ($signedSenderGroups as $senderId => $signedSenderDocuments) {
            $senderInfo = [
                'name' => $signedSenderDocuments->first()->usu_nombre . ' ' . $signedSenderDocuments->first()->usu_apelli,
                'email' => $signedSenderDocuments->first()->usu_email
            ];

            $signedSendCount = $signedSenderDocuments->count();

            $signedSendersCount->push([
                'sender' => $senderInfo,
                'send_count' => $signedSendCount
            ]);
        }

        $topSignedSenders = $signedSendersCount->sortByDesc('send_count')->take(5)->values()->all();

        $response = [
            'top_senders' => $topSenders,
            'top_signed_senders' => $topSignedSenders,
            'monthly_counts' => $monthlyCounts,
        ];

        return response()->json($response);
    }
}
