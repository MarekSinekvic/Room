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
        Schema::create('users_comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('comment');

            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on("users");

            $table->foreignId('writing_id')->nullable();
            $table->foreign('writing_id')->references('id')->on("writings");
            
            $table->foreignId('comment_id')->nullable();
            $table->foreign('comment_id')->references('id')->on("users_comments");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
