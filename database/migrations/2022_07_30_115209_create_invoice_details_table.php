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
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id')->index();
            $table->text('sender_street')->nullable();
            $table->string('sender_city', 100)->nullable();
            $table->string('sender_postCode', 100)->nullable();
            $table->string('sender_country', 100)->nullable();
            $table->text('client_street')->nullable();
            $table->string('client_city', 100)->nullable();
            $table->string('client_postCode', 100)->nullable();
            $table->string('client_country', 100)->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
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
        Schema::dropIfExists('invoice_details');
    }
};
