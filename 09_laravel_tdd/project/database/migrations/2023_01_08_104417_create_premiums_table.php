<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premiums', function (Blueprint $table) {
            $table->id();
            $table->integer('scratches_left');      // ile zdrapan pozostalo
            $table->string('scratchcard_id');                // id zdrapek
            $table->integer('user_id');             // id user
            $table->string('expiration_date');      // kiedy kolejna bedzie mozna zdrapac
            $table->boolean('harakiried');          // do usuwania konta
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
        Schema::dropIfExists('premiums');
    }
};
