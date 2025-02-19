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
        Schema::create('writing_content_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('description');
        });
        DB::table('writing_content_types')->insert(['description'=>'not defined type']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
