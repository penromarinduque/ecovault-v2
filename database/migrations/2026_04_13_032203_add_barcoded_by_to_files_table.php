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
        Schema::table('files', function (Blueprint $table) {
            //
            $table->foreignId('barcoded_by')->nullable();
            $table->foreignId("released_by")->nullable();
            $table->foreignId("date_barcoded")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            //
            $table->dropColumn('barcoded_by');
            $table->dropColumn('released_by');
            $table->dropColumn('date_barcoded');
        });
    }
};
