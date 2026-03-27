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
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->longText('description')->nullable();
            $table->string('race');
            $table->string('wesen');
            $table->string('leiteigenschaft1');
            $table->string('leiteigenschaft2');
            $table->integer('main_stat_value')->nullable();
            $table->boolean('ko_toggle')->nullable();
            $table->string('archetype')->nullable();
            $table->json('racial_traits')->nullable();
            $table->integer('ko_bonus')->nullable();
            $table->integer('bonus_lep')->nullable();
            $table->integer('bonus_sep')->nullable();
            $table->integer('bonus_ini')->nullable();
            $table->integer('bonus_re')->nullable();
            $table->integer('ko');
            $table->integer('st');
            $table->integer('ag');
            $table->integer('ge');
            $table->integer('we');
            $table->integer('in');
            $table->integer('mu');
            $table->integer('ch');
            $table->integer('ko_sum')->nullable();
            $table->integer('st_sum')->nullable();
            $table->integer('ag_sum')->nullable();
            $table->integer('ge_sum')->nullable();
            $table->integer('we_sum')->nullable();
            $table->integer('in_sum')->nullable();
            $table->integer('mu_sum')->nullable();
            $table->integer('ch_sum')->nullable();
            $table->integer('zähigkeit_sum')->nullable();
            $table->integer('kraftakt_sum')->nullable();
            $table->integer('körperbeherrschung_sum')->nullable();
            $table->integer('fingerfertigkeit_sum')->nullable();
            $table->integer('konzentration_sum')->nullable();
            $table->integer('wahrnehmung_sum')->nullable();
            $table->integer('willenskraft_sum')->nullable();
            $table->integer('kommunikation_sum')->nullable();
            $table->json('skill_weapon')->nullable();
            $table->json('skill_aspect')->nullable();
            $table->integer('leps');
            $table->integer('tragkraft');
            $table->integer('gs_leib');
            $table->integer('gs_seele');
            $table->integer('handwerksbonus');
            $table->integer('kontrollwiderstand');
            $table->integer('initiative');
            $table->integer('verteidigung');
            $table->integer('seelenpunkte');
            $table->json('nw_gattung')->nullable();
            $table->string('nw_quality')->nullable();
            $table->json('nw_damage_type')->nullable();
            $table->integer('nw_aw')->nullable();
            $table->integer('nw_vw')->nullable();
            $table->integer('nw_tw')->nullable();
            $table->integer('xp');
            $table->json('classability1')->nullable();
            $table->json('classability2')->nullable();
            $table->json('classability3')->nullable();
            $table->json('handwerkskenntnisse')->nullable();
            $table->json('lore')->nullable();
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
