<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_headers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('outlet_id')->nullable();
            $table->unsignedBigInteger('storage_id')->nullable();
            $table->timestamp('delivery_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('posting_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->float('purchase_amount')->default(0);
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('vendors');
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('outlet_id')->references('id')->on('outlets');
            $table->foreign('storage_id')->references('id')->on('storages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_headers');
    }
}
