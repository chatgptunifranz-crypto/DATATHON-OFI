<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesDelDiaTable extends Migration
{    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ordenes_del_dia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha');
            $table->longText('contenido');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            
            // Foreign key constraint para la relación con usuarios
            if (Schema::hasTable('users')) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordenes_del_dia');
    }
}
