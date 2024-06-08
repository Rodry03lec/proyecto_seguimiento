<?php

namespace App\Models\Tramite;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta_archivado extends Model
{
    use HasFactory;
    protected $table = 'rl_ruta_archivado';
    protected $fillable=[
        'descripcion',
        'id_hoja_ruta',
    ];

    //relacion reversa con Hoja_ruta
    public function hoja_ruta(){
        return $this->belongsTo(Hojas_ruta::class, 'id_hoja_ruta', 'id');
    }
}
