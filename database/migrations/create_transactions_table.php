<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('order_id'); // Foreign key to the orders table
            $table->decimal('amount', 10, 3); // Amount with up to 3 decimal places
            $table->string('currency', 3)->default('OMR'); // Currency, e.g., OMR, USD
            $table->string('status', 20)->default('INITIATED'); // Status of the transaction
            $table->string('tracking_id', 100)->nullable(); // Tracking ID returned by the payment gateway
            $table->text('response')->nullable(); // Full response from the gateway
            $table->timestamps(); // Created at and Updated at timestamps

            // Define foreign key
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
