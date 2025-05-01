<?php

use App\Models\System;
use App\Models\Equipment;
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
            $table->text('description')->nullable();
            $table->foreignIdFor(System::class)->constrained();
            $table->string('race');
            $table->string('wesen');
            $table->string('leiteigenschaft1');
            $table->string('leiteigenschaft2');
            $table->integer('main_stat_value')->nullable();
            $table->string('archetype')->nullable();
            $table->json('rassenmerkmale')->nullable();
            $table->integer('ko');
            $table->integer('st');
            $table->integer('ag');
            $table->integer('ge');
            $table->integer('we');
            $table->integer('in');
            $table->integer('mu');
            $table->integer('ch');
            $table->json('skill_ko')->nullable();
            $table->json('skill_st')->nullable();
            $table->json('skill_ag')->nullable();
            $table->json('skill_ge')->nullable();
            $table->json('skill_we')->nullable();
            $table->json('skill_in')->nullable();
            $table->json('skill_mu')->nullable();
            $table->json('skill_ch')->nullable();
            $table->integer('leps');
            $table->integer('tragkraft');
            $table->integer('geschwindigkeit');
            $table->integer('handwerksbonus');
            $table->integer('kontrollwiderstand');
            $table->integer('initiative');
            $table->integer('verteidigung');
            $table->integer('seelenpunkte');
            $table->string('nw_gattung');
            $table->string('nw_quality');
            $table->json('nw_damage_type')->nullable();
            $table->integer('nw_aw');
            $table->integer('nw_vw');
            $table->integer('nw_tw');
            $table->integer('xp');
            $table->json('klassenfertigkeiten')->nullable();
            $table->json('handwerkskenntnisse')->nullable();
            $table->json('lore')->nullable();
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
