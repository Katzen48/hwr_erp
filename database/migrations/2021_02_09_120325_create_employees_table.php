<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->string('first_name')->default(0);
            $table->string('last_name')->default('');
            $table->string('position')->default('');
            $table->boolean('purchaser')->default(false);
            $table->boolean('salesperson')->default(false);
            $table->timestamps();

            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
