<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rl_tramite', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_creada');
            $table->time('hora_creada');
            $table->dateTime('hora_hora_creada');
            $table->string('cite', 100);
            $table->string('cite_texto');
            $table->integer('numero_hojas')->nullable();
            $table->integer('numero_anexos')->nullable();
            $table->unsignedBigInteger('id_estado'); //la primera relacion
            $table->string('referencia');
            $table->string('remitente_nombre');
            $table->string('remitente_cargo');
            $table->string('remitente_sigla');
            $table->string('remitente_txt');
            $table->unsignedBigInteger('id_tipo_prioridad');
            $table->unsignedBigInteger('id_tipo_tramite');
            $table->string('destinatario_nombre');
            $table->string('destinatario_cargo');
            $table->string('destinatario_sigla');
            $table->string('destinatario_txt');
            $table->year('gestion');
            $table->string('numero_unico', 50);
            $table->string('codigo', 50);
            $table->string('observacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rl_tramite');
    }
};
