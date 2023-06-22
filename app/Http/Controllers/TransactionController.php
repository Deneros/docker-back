<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index() {
        return Transaction::orderBy('StartingDate', 'desc')->get();
    }

    public function get($id) {
        $transaction = Transaction::find($id);

        $IdNumber = $transaction->IdNumber;
        $TransactionId = $transaction->TransactionId;

        $search_face = "images/{$IdNumber}/{$TransactionId}/clientFace.png";
        $search_front_document = "images/{$IdNumber}/{$TransactionId}/frontCroppedDocument.png";
        $search_back_document = "images/{$IdNumber}/{$TransactionId}/backCroppedDocument.png";

        $transaction->ImagesTransaction = [
            "clientFace" => $this->getImageBase64($search_face),
            "frontCroppedDocument" => $this->getImageBase64($search_front_document),
            "backCroppedDocument" => $this->getImageBase64($search_back_document)
        ];

        return $transaction;
    }

    private function getImageBase64($path) {
        $base64Image = null;

        if (Storage::has($path)) {
            try {
                $imageContents = Storage::read($path);
                $base64Image = base64_encode($imageContents);
            } catch (\Exception $e) { }
        }

        return $base64Image;
    }
}
