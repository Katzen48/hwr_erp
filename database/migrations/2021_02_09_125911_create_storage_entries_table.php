<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStorageEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('storage_entries', function (Blueprint $table) {
            $table->id('entry_no');
            $table->unsignedBigInteger('storage_id');
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
            $table->timestamp('delivery_date')->nullable();
            $table->integer('quantity');
            $table->unsignedBigInteger('applies_to_entry')->nullable();
            $table->integer('remaining_quantity');
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->foreign('storage_id')->references('id')->on('storages')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('item_variant_id')->references('id')->on('item_variants')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('employee_id')->references('id')->on('employees')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('applies_to_entry')->references('entry_no')->on('storage_entries')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_entries');
    }
}
