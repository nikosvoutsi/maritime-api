<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('voyages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vessel_id');
            $table->string('code');
            $table->timestamp('start');
            $table->timestamp('end')->nullable();
            $table->string('status');
            $table->decimal('revenues', 8, 2)->nullable();
            $table->decimal('expenses', 8, 2)->nullable();
            $table->decimal('profit', 8, 2)->nullable();
            $table->timestamps();

            $table->foreign('vessel_id')->references('id')->on('vessels');
            $table->unique(['vessel_id', 'start', 'end']);
            $table->checkIn('status', ['pending', 'ongoing', 'submitted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vessel_opex');
    }
};
