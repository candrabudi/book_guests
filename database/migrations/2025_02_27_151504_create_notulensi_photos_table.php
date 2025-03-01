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
        Schema::create('notulensi_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notulensi_id');
            $table->string('photo_path');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('file_extension');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notulensi_photos');
    }
};
