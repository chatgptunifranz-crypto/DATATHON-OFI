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
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            // Datos personales básicos
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('ci')->unique();
            $table->date('fecha_nacimiento');
            $table->string('expedido'); // Departamento de expedición
            $table->enum('estado_civil', ['soltero', 'casado', 'divorciado', 'viudo', 'union_libre'])->default('soltero');
            $table->string('profesion')->nullable();
            $table->text('domicilio');
            
            // Datos del registro policial
            $table->string('cargo'); // Tipo de delito o infracción
            $table->text('descripcion')->nullable();
            $table->string('foto')->nullable();
            $table->text('antecedentes')->nullable();
            
            // Ubicación geográfica del incidente
            $table->decimal('longitud', 10, 7)->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
