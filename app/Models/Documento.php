<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = 'documento';
    protected $primaryKey = 'doc_id';
    public $timestamps = false;

    protected $fillable = [
        'doc_nombre', 
        'doc_ruta', 
        'doc_estado', 
        'doc_usuari', 
        'doc_fechac', 
        'doc_horac', 
        'doc_fecha_e', 
        'doc_fecha_r', 
        'doc_fecha_f', 
        'doc_hora_f', 
        'doc_hash', 
        'doc_md5', 
        'certificadofirma_hash', 
        'certificadofirma_md5', 
        'id_carpeta'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'doc_usuari', 'usu_id');
    }

    public function detalles()
    {
        return $this->hasMany(DocumentoDetalle::class, 'det_docume', 'doc_id');
    }
}
