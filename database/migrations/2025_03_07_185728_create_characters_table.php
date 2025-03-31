<?php

use App\Models\System;
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

        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignIdFor(System::class)->constrained();
            $table->string('race');
            $table->string('leiteigenschaft1');
            $table->string('leiteigenschaft2');
            $table->json('rassenmerkmale')->nullable();
            $table->integer('ko');
            $table->integer('st');
            $table->integer('ag');
            $table->integer('ge');
            $table->integer('we');
            $table->integer('in');
            $table->integer('mu');
            $table->integer('ch');
            $table->integer('leps');
            $table->integer('tragkraft');
            $table->integer('geschwindigkeit');
            $table->integer('handwerksbonus');
            $table->integer('kontrollwiderstand');
            $table->integer('initiative');
            $table->integer('verteidigung');
            $table->integer('seelenpunkte');
            $table->integer('experience-level');
            $table->string('lore');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }  
};