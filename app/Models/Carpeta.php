<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carpeta extends Model{

    protected $table = 'documento';
    protected $primaryKey = 'doc_id';
    public $timestamps = false;

    protected $fillable = [
        'carp_nombre', 
        'carp_fecha', 
        'carp_hora', 
        'carp_contador', 
        'usu_id' 
    ];

    public function usuario(){
        return $this->belongsTo(User::class,'usu_id','usu_id');
    }


}

