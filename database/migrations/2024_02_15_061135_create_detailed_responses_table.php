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
        Schema::create('detailed_responses', function (Blueprint $table) {
            $table->id();

            $table->text('answer')->nullable();

            $table->unsignedBigInteger('survey_id');
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('element_id');
            $table->unsignedBigInteger('choice_id');
            $table->unsignedBigInteger('response_id');

            $table->foreign('response_id')->references('id')->on('responses')->onDelete('cascade');
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('element_id')->references('id')->on('elements')->onDelete('cascade');
            $table->foreign('choice_id')->references('id')->on('choices')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detailed_responses');
    }
};
