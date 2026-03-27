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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('quality')->nullable();;
            $table->string('item_type')->nullable();
            $table->integer('hwp')->nullable();
            $table->integer('attackvalue')->nullable();
            $table->json('damage_type')->nullable();
            $table->string('kontrollwiderstand')->nullable();
            $table->string('count_dice')->nullable();
            $table->string('tw')->nullable();
            $table->json('waffenführung')->nullable();
            $table->string('waffengattung')->nullable();
            $table->integer('traglast')->nullable();
            $table->integer('pVW')->nullable();
            $table->integer('wp_vw')->nullable();
            $table->integer('schild_verteidigung')->nullable();
            $table->integer('armor_schnitt')->nullable();
            $table->integer('armor_stumpf')->nullable();
            $table->integer('armor_stich')->nullable();
            $table->integer('armor_elementar')->nullable();
            $table->integer('armor_arcan')->nullable();
            $table->integer('armor_chaos')->nullable();
            $table->integer('armor_spirit')->nullable();
            $table->integer('kw')->nullable();
            $table->integer('charm_arcan')->nullable();
            $table->integer('charm_chaos')->nullable();
            $table->integer('charm_spirit')->nullable();
            $table->integer('shield_stumpf')->nullable();
            $table->integer('shield_stich')->nullable();
            $table->integer('shield_schnitt')->nullable();
            $table->integer('shield_elementar')->nullable();
            $table->integer('shield_arcan')->nullable();
            $table->integer('shield_chaos')->nullable();
            $table->integer('shield_spirit')->nullable();
            $table->integer('offensivschild')->nullable();
            $table->json('enchantment')->nullable();
            $table->string('enchantment_qs')->nullable();
            $table->json('wp_erweiterungen')->nullable();
            $table->json('rs_erweiterungen')->nullable();
            $table->json('ts_erweiterungen')->nullable();
            $table->json('sd_erweiterungen')->nullable();
            $table->foreignId('character_id')->nullable()->constrained()->onDelete('set Null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
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
