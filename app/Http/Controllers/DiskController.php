<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Illuminate\Http\Response;



class DiskController extends Controller
{
    public function downloadObject(string $id)
    {

        try {
            $documento = Document::select('doc_nombre', 'doc_ruta', 'doc_estado')->where('doc_id', $id)->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Documento no encontrado.'], Response::HTTP_NOT_FOUND);
        }


        $cliente = env('APP_CLIENT');
        $estado = strtolower($documento['doc_estado']) . 's';
        $nombre = str_contains($documento['doc_ruta'], '/') ? $documento['doc_nombre'] : $documento['doc_ruta'];

        // echo($documento['doc_estado']);
        $search_doc = "{$cliente}/{$estado}/{$nombre}";

        if (!Storage::exists($search_doc)) {
            return response()->json(['error' => 'Archivo no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $content = Storage::get($search_doc);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el contenido del archivo.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // $headers = [
        //     'Content-Type' => Storage::mimeType($search_doc),
        //     'Content-Disposition' => 'attachment; filename="' . $nombre . '"',
        // ];

        // echo $search_doc;

        return response()->json(['document' => base64_encode($content)], Response::HTTP_OK);
        // $content = Storage::get($search_doc);
        // Storage::put('test.text', 'S3 Testing with laravel');
        // var_dump($content);
    }

    public function downloadObjectAzure(string $id)
    {

        try {
            $documento = Document::select('doc_nombre', 'doc_ruta', 'doc_estado')->where('doc_id', $id)->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Documento no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $estado = strtolower($documento['doc_estado']) . 's';
        $nombre = str_contains($documento['doc_ruta'], '/') ? $documento['doc_nombre'] : $documento['doc_ruta'];

        $search_doc = "{$estado}/{$nombre}";

        if (!Storage::has($search_doc)) {
            return response()->json(['error' => 'Archivo no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $content = Storage::read($search_doc);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el contenido del archivo.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $headers = [
            'Content-Type' => Storage::mimeType($search_doc),
            'Content-Disposition' => 'attachment; filename="' .  'firmadoc-02122022144806-prueba.pdf' . '"',
        ];

        return response($content, Response::HTTP_OK, $headers);
    }

    public function b64ObjectAzure(string $id)
    {
        // return $id;

        try {
            $documento = Document::select('doc_nombre', 'doc_ruta', 'doc_estado')->where('doc_id', $id)->firstOrFail();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Documento no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        $estado = strtolower($documento['doc_estado']) . 's';
        $nombre = str_contains($documento['doc_ruta'], '/') ? $documento['doc_nombre'] : $documento['doc_ruta'];

        $search_doc = "{$estado}/{$nombre}";

        if (!Storage::has($search_doc)) {
            return response()->json(['error' => 'Archivo no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $content = Storage::read($search_doc);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el contenido del archivo.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // $headers = [
        //     'Content-Type' => Storage::mimeType($search_doc),
        //     'Content-Disposition' => 'attachment; filename="' .  'firmadoc-02122022144806-prueba.pdf' .'"',
        // ];

        return response()->json(['document' => base64_encode($content)], Response::HTTP_OK);
    }

    public function getStorage(int $id)
    {
        if (!Storage::has("certificados/Firmacertificado-{$id}.pdf")) {
            return response()->json(['error' => 'Archivo no encontrado.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $content = Storage::read("certificados/Firmacertificado-{$id}.pdf");
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el contenido del archivo.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $headers = [
            'Content-Type' => Storage::mimeType("certificados/Firmacertificado-{$id}.pdf"),
            'Content-Disposition' => 'attachment; filename="' .  'firmadoc-02122022144806-prueba.pdf' . '"',
        ];

        return response($content, Response::HTTP_OK, $headers);
    }
}
