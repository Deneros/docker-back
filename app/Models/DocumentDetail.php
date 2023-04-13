<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentDetail extends Model
{
    protected $table = 'detalledocumento';
    protected $primaryKey = 'det_id';
    public $timestamps = false;

    protected $fillable = [
        'det_docume', 
        'det_firma', 
        'det_observ',
        'det_nomdes',
        'det_cordes',
        'link_firma', 
        'det_rutafi', 
        'doc_fechaf',
        'doc_horaf', 
        'det_ip', 
        'det_usuari', 
        'codigo_verificacion',
        'TransactionId', 
        'IdNumber', 
        'estado_firma_destinatario_modal'
    ];

    public function document()
    {
        return $this->hasMany(Document::class, 'doc_id', 'det_docume');
    }
}
