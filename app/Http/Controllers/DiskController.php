<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Documento;
use Illuminate\Http\Response;



class DiskController extends Controller
{
    public function downloadObject(string $id){

        try {
            $documento = Documento::select('doc_nombre', 'doc_ruta', 'doc_estado')->where('doc_id', $id)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Documento no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $cliente = env('APP_CLIENT');
        $estado = strtolower($documento['doc_estado']).'s';
        $nombre = str_contains($documento['doc_ruta'],'/') ? $documento['doc_nombre'] : $documento['doc_ruta'];

        // echo($documento['doc_estado']);
        $search_doc = "{$cliente}/{$estado}/{$nombre}";

        if (!Storage::exists($search_doc)) {
            return response()->json(['error' => 'Archivo no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $content = Storage::get($search_doc);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error al obtener el contenido del archivo.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $headers = [
            'Content-Type' => Storage::mimeType($search_doc),
            'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
        ];

        // echo $search_doc;

        return response($content, Response::HTTP_OK, $headers);
        // $content = Storage::get($search_doc);
        // Storage::put('test.text', 'S3 Testing with laravel');
        // var_dump($content);
    }
}
