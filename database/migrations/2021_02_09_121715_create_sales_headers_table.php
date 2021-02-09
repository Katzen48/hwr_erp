<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSalesHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_headers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable();
            $table->float('order_amount')->default(0);
            $table->timestamp('posting_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('storage_id')->references('id')->on('storages')->onDelete('RESTRICT')->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_headers');
    }
}
