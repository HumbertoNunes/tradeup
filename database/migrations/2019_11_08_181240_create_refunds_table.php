<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestampTz('date');
            $table->string('type');
            $table->string('description');
            $table->double('value', 8, 2);
            $table->boolean('approved')->default(false);
            $table->string('image')->nullable();
            $table->softDeletes();
            $table->unsignedBigInteger('person_id');
            $table->foreign('person_id')
                ->references('id')
                ->on('people')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refunds');
    }
}
