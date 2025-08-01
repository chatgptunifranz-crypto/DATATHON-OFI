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
        Schema::create('aprobacion_del_dias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_del_dia_id');
            $table->enum('estado', ['pendiente', 'aprobado'])->default('pendiente');
            $table->unsignedBigInteger('usuario_creador_id')->nullable(); // Usuario que creó la aprobación
            $table->unsignedBigInteger('usuario_aprobador_id')->nullable(); // Usuario que aprobó (comandante)
            $table->text('observaciones')->nullable(); // Para marcar errores o comentarios
            $table->text('errores_detectados')->nullable(); // Lista específica de errores
            $table->timestamp('fecha_aprobacion')->nullable(); // Fecha de aprobación
            $table->timestamps();

            // Foreign keys
            $table->foreign('orden_del_dia_id')->references('id')->on('ordenes_del_dia')->onDelete('cascade');
            $table->foreign('usuario_creador_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('usuario_aprobador_id')->references('id')->on('users')->onDelete('set null');
            
            // Índices para mejor rendimiento
            $table->index(['estado', 'fecha_aprobacion']);
            $table->index(['usuario_aprobador_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aprobacion_del_dias');
    }
};
