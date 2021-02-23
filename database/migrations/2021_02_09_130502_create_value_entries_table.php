<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateValueEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('value_entries', function (Blueprint $table) {
            $table->id('entry_no');
            $table->unsignedBigInteger('outlet_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('item_variant_id');
            $table->string('item_description');
            $table->string('item_variant_description');
            $table->string('source_doc_type');
            $table->unsignedBigInteger('source_doc_id');
            $table->unsignedBigInteger('source_doc_line_no');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamp('posting_date');
            $table->float('unit_price');
            $table->float('vat_percent');
            $table->float('vat_amount');
            $table->float('line_amount');
            $table->unsignedBigInteger('vendor_id')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamps();

            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('item_variant_id')->references('id')->on('item_variants')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('NO ACTION')->onUpdate('NO ACTION');
            $table->index('posting_date');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('value_entries');
    }
}
