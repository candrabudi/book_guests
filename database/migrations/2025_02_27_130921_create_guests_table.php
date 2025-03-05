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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->time('time');
            $table->foreignId('identity_id');
            $table->foreignId('institution_id'); 
            $table->integer('companion_id')->nullable();
            $table->enum('appointment', ['yes', 'no']);
            $table->text('purpose');
            $table->integer('total_audience');
            $table->integer('queue_number');
            $table->enum('status', ['pending', 'disposition', 'accepted', 'rejected', 'reschedule']);
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
