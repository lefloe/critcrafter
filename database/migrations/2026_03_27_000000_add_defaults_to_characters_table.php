<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->integer('leps')->default(0)->change();
            $table->integer('tragkraft')->default(0)->change();
            $table->integer('gs_leib')->default(0)->change();
            $table->integer('gs_seele')->default(0)->change();
            $table->integer('handwerksbonus')->default(0)->change();
            $table->integer('kontrollwiderstand')->default(0)->change();
            $table->integer('initiative')->default(0)->change();
            $table->integer('verteidigung')->default(0)->change();
            $table->integer('seelenpunkte')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('characters', function (Blueprint $table) {
            $table->integer('leps')->default(null)->change();
            $table->integer('tragkraft')->default(null)->change();
            $table->integer('gs_leib')->default(null)->change();
            $table->integer('gs_seele')->default(null)->change();
            $table->integer('handwerksbonus')->default(null)->change();
            $table->integer('kontrollwiderstand')->default(null)->change();
            $table->integer('initiative')->default(null)->change();
            $table->integer('verteidigung')->default(null)->change();
            $table->integer('seelenpunkte')->default(null)->change();
        });
    }
};
