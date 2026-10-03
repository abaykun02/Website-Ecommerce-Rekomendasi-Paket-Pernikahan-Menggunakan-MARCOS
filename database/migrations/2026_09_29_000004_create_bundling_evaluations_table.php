<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bundling_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundling_id')->constrained('bundlings')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('criterias')->cascadeOnDelete();
            $table->integer('score'); // conversion score: 1, 3, or 5
            $table->timestamps();

            $table->unique(['bundling_id', 'criteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundling_evaluations');
    }
};
