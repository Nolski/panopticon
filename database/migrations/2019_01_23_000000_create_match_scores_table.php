<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match_scores', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('job_opening_id')->index();
            $table->unsignedInteger('job_seeker_id')->index();

            $table->double('score')->default(0);

            $table->foreign('job_opening_id')->references('id')->on('job_openings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('job_seeker_id')->references('id')->on('job_seekers')->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('match_scores');
    }
}
