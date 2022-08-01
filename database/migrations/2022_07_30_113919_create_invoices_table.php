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
        Schema::create('invoices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('clientName', 100)->nullable();
            $table->string('clientEmail', 100)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('paymentTerms')->nullable();
            $table->string('paymentDue')->nullable();
            $table->enum('status', ['draft', 'pending', 'paid'])->nullable();
            $table->double('total')->nullable();
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
        Schema::dropIfExists('invoices');
    }
};
