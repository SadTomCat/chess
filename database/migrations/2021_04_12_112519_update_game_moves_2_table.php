<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGameMoves2Table extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE game_moves MODIFY type ENUM('peace', 'capture', 'promotion', 'castling', 'mate', 'aisle') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE game_moves MODIFY type ENUM('peace', 'capture', 'promotion', 'castling', 'mate') NOT NULL");
    }
}
