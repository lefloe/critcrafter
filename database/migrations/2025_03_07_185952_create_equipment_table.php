<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Character;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('quality');
            $table->string('item_type');
            $table->integer('hwp')->nullable(); // Handwerkspunkte
            $table->string('waffengattung')->nullable();
            $table->integer('angriffswert')->nullable();
            $table->json('damage_type')->nullable();
            $table->integer('trefferwuerfel')->nullable();
            $table->integer('traglast')->nullable();
            $table->integer('passive_verteidigung')->nullable();
            $table->integer('schild_verteidigung')->nullable();
            $table->integer('rs_schnitt')->nullable();
            $table->integer('rs_stumpf')->nullable();
            $table->integer('rs_stich')->nullable();
            $table->integer('rs_elementar')->nullable();
            $table->integer('kontrollwiderstand')->nullable();
            $table->integer('rs_arcan')->nullable();
            $table->integer('rs_chaos')->nullable();
            $table->integer('rs_spirit')->nullable();
            $table->json('verzauberungen')->nullable();
            $table->json('erweiterungen')->nullable(); 
            $table->json('rs_erweiterungen')->nullable();
            $table->json('ts_erweiterungen')->nullable();
            $table->timestamps();
            $table->foreignIdFor(Character::class)->nullable()->constrained()->onDelete('set Null');
            $table->boolean('equipped')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
