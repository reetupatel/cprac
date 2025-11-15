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
        Schema::create('students', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('email')->nullable();
            $t->string('phone')->nullable();
            $t->integer('age')->nullable();
            $t->string('gender')->nullable();
            $t->json('skills')->nullable();
            $t->string('country')->nullable();
            $t->json('languages')->nullable();
            $t->date('dob')->nullable();
            $t->time('preferred_time')->nullable();
            $t->integer('hours')->nullable();
            $t->string('fav_color')->nullable();
            $t->string('photo')->nullable();
            $t->text('about')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
