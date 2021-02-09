<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_lines', function (Blueprint $table) {
            $table->unsignedBigInteger('line_no');
            $table->unsignedBigInteger('purchase_header_id');
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

            $table->foreign('purchase_header_id')->references('id')->on('PurchaseHeaders')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreign('item_variant_id')->references('id')->on('itemVariants')->onDelete('RESTRICT')->onUpdate('RESTRICT');
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
        Schema::dropIfExists('purchase_lines');
    }
}
