<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('local_storage_id');
            $table->unsignedBigInteger('shipping_storage_id');
            $table->string('description')->default('');
            $table->string('address')->default('');
            $table->string('postcode')->default('');
            $table->string('country')->default('');
            $table->timestamps();

            $table->foreign("local_storage_id")->references('id')->on("storage");
            $table->foreign("shipping_storage_id")->references('id')->on("storage");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlets');
    }
}
