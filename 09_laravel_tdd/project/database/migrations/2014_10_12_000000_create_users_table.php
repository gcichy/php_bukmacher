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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('password');
            $table->string('nickname')->unique();        // rozne dla kazdego uzytkownika
            $table->string('email')->unique();           // rozne dla kazdego uzytkownika
            $table->string('phone_number')->unique();    // string poniewaz przed numerem, jest kierunek np. "+48500200300"
            $table->string('person_number')->unique(); // rowniez string poniewaz moze zaczynac sie od 0
            $table->double('deposit');                   // double, poniewaz mozna miec grosze, np. 20.50
            $table->boolean('premium');                  // czy jest uzytkownikiem premium
            $table->boolean('confirmed');                // czy konto jest potwierdzone
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
        Schema::dropIfExists('users');
    }
};
