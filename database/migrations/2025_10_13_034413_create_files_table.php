<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name', 5000);
            $table->string('file_name');
            $table->foreignId('folder_id');
            $table->string('released_to', 255)->nullable();  // ← added
            $table->string('action', 255)->nullable();       // ← added
            $table->string('doc_control_no', 100)->nullable();
            $table->string('office_source', 1000);
            $table->string('barcode_no', 255)->nullable();   // ← added
            $table->integer('order_no')->default(0);         // ← added
            $table->string('file_type', 100)->nullable();
            $table->foreignId('doc_classification_id');
            $table->datetime('date_released');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};