<?php

namespace App\Models\Tramite;

use App\Models\Configuracion_tramite\Tipo_estado;
use App\Models\Configuracion_tramite\Tipo_prioridad;
use App\Models\Configuracion_tramite\Tipo_tramite;
use App\Models\Configuracion_tramite\User_cargo_tramite;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tramite extends Model
{
    use HasFactory;
    protected $table = 'rl_tramite';
    protected $fillable=[
        'fecha_creada',
        'hora_creada',
        'fecha_hora_creada',
        'cite',
        'cite_texto',
        'numero_hojas',
        'numero_anexos',
        'referencia',
        'remitente_nombre',
        'remitente_cargo',
        'remitente_sigla',
        'remitente_txt',
        'destinatario_nombre',
        'destinatario_cargo',
        'destinatario_sigla',
        'destinatario_txt',
        'gestion',
        'numero_unico',
        'codigo',
        'observacion',
        'id_tipo_prioridad',
        'id_tipo_tramite',
        'id_estado',
        'remitente_id',
        'destinatario_id',
        'user_cargo_id',
    ];

    protected function referencia(): Attribute{
        return new Attribute(
            set: fn ($value) => mb_strtoupper($value, 'UTF-8'),
            get: fn ($value) => mb_strtoupper($value, 'UTF-8'),
        );
    }



    //relacion de uno amuchos
    public function hojas_ruta(){
        return $this->hasMany(Hojas_ruta::class, 'tramite_id', 'id');
    }

    //relacion tipo_prioridad
    public function tipo_prioridad(){
        return $this->belongsTo(Tipo_prioridad::class, 'id_tipo_prioridad', 'id');
    }

    //relacion tipo_tramite
    public function tipo_tramite(){
        return $this->belongsTo(Tipo_tramite::class, 'id_tipo_tramite', 'id');
    }

    //relacion con tipo_estado
    public function estado_tipo(){
        return $this->belongsTo(Tipo_estado::class, 'id_estado', 'id');
    }

    //relacion con remitente user_cargo_tram
    public function remitente_user(){
        return $this->belongsTo(User_cargo_tramite::class, 'remitente_id', 'id');
    }

    //relacion con destinatario user_cargo_tram
    public function destinatario_user(){
        return $this->belongsTo(User_cargo_tramite::class, 'destinatario_id', 'id');
    }

    //relacion con cargo user_cargo_tram
    public function user_cargo_tramite(){
        return $this->belongsTo(User_cargo_tramite::class, 'user_cargo_id', 'id');
    }
}
