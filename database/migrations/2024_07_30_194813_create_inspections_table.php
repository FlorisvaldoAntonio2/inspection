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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('description', 1000)->nullable(false);
            $table->timestamp('inspection_start')->nullable(false);
            $table->timestamp('inspection_end')->nullable(false);
            $table->integer('attempts_per_operator')->nullable(false);
            $table->integer('quantity_pieces')->nullable(true);
            $table->boolean('enabled')->default(false); //liberada para inspeção dos operadores
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
