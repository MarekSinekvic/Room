<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('writings_images', function (Blueprint $table) {
            $table->id();
            $table->string('url',1024);
            $table->timestamps();
            // $table->timestamp('updated_at')->default('current_timestamp');
            // $table->timestamp('created_at')->default('current_timestamp');

            $table->foreignId('writing_id');
            $table->foreign('writing_id')->references('id')->on('writings');
            
        });
        Schema::table('writings', function (Blueprint $table) {
            $table->foreignId('preview_image_id')->nullable()->default(null);
            $table->foreign('preview_image_id')->references('id')->on('writings_images')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('writings_images');
    }
};
