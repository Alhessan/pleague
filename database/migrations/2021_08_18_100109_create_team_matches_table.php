<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('match_id');
            $table->integer('result')->default(0);// 0 losed, 1 equality, 3 Draw up
            $table->integer('type')->default(0);// 0 home, 1 guest
            $table->integer('goals_count')->default(0);//0 for loss
            $table->timestamps();
            $table->foreign('team_id')->references('id')->on('teams');
            $table->foreign('match_id')->references('id')->on('matches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_matches');
    }
}
