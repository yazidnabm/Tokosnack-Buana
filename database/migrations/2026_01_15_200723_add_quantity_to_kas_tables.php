<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantityToKasTables extends Migration
{
    public function up(): void
    {
        Schema::table('kas_masuk', function (Blueprint $table) {
            $table->integer('quantity')->default(1);
            $table->dropColumn('keterangan');
        });

        Schema::table('kas_keluar', function (Blueprint $table) {
            $table->integer('quantity')->default(1);
            $table->dropColumn('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('kas_masuk', function (Blueprint $table) {
            $table->text('keterangan')->nullable();
            $table->dropColumn('quantity');
        });

        Schema::table('kas_keluar', function (Blueprint $table) {
            $table->text('keterangan')->nullable();
            $table->dropColumn('quantity');
        });
    }
}
