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
        Schema::create('lanterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_spaces_id')->constrained('parking_spaces')->cascadeOnDelete();
            $table->boolean('toggle')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lanterns');
    }
};
