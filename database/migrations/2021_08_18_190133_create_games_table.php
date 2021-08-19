<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->unsignedInteger('week_id');
            $table->unsignedInteger('guest_team_id');
            $table->integer('guest_goals');
            $table->unsignedInteger('home_team_id');
            $table->integer('home_goals');
            $table->boolean('is_played')->default(false);
            $table->timestamps();
            $table->foreign('week_id')->references('id')->on('weeks');
            $table->foreign('guest_team_id')->references('id')->on('teams');
            $table->foreign('home_team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
