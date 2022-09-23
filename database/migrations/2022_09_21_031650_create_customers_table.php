<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 75);
            $table->string('cpf', 14);
            $table->boolean('negative');
            $table->float('salary', 8, 2);
            $table->float('card_limit', 8, 2);
            $table->float('rent_value', 8, 2);
            $table->string('road', 120);
            $table->integer('number');
            $table->string('city', 75);
            $table->string('federative_unit', 2);
            $table->string('cep', 9);
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
        Schema::dropIfExists('customers');
    }
};
