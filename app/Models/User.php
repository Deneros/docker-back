<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'usu_id';
    public $timestamps = false;


    protected $fillable = [
        'usu_estado', 
        'rol_usuario', 
        'usu_nombre', 
        'usu_apelli',
        'usu_tipo_documento', 
        'usu_docume', 
        'usu_email', 
        'usu_celula', 
        'usu_terminos',
        'usu_fechac', 
        'usu_horac', 
        'usu_token', 
        'usu_codigo', 
        'usu_codigo_vivo',
        'usu_fecham', 
        'usu_horam', 
        'usu_detm', 
        'usu_rutafi', 
        'usu_rutafidi', 
        'id_api'
    ];

    /* protected $casts = [
        'usu_fechac' => 'datetime:Y-m-d',
        'usu_horac' => 'timestamp',
        'usu_fecham' => 'datetime:Y-m-d',
        'usu_horam' => 'timestamp'
    ]; */

    protected $hidden = [
        'usu_password',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'doc_usuari', 'usu_id');
    }

    public function getCoverAttribute()
    {
        return 10;
    }

    public function getFirmasAttribute()
    {
        $documents = $this->documents();

        return 0;
    }
}
