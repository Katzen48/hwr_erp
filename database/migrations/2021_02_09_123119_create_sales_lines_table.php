<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_lines', function (Blueprint $table) {
            $table->unsignedBigInteger('sales_header_id');
            $table->unsignedBigInteger('line_no');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('item_variant_id')->nullable();
            $table->string('description')->default('');
            $table->float('unit_price')->default(0);
            $table->float('vat_percent')->default(0);
            $table->float('vat_amount')->default(0);
            $table->integer('quantity')->default(0);
            $table->float('line_amount')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->primary(['sales_header_id', 'line_no']);

            $table->foreign('sales_header_id')->references('id')->on('sales_headers')->onDelete('CASCADE')->onUpdate('RESTRICT');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('item_variant_id')->references('id')->on('item_variants')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('RESTRICT')->onUpdate('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_lines');
    }
}
