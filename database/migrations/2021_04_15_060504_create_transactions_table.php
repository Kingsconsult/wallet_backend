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
            $table->id();
            $table->double('amount', 10, 2);
            $table->string('transaction_type', 255);
            $table->unsignedBigInteger('credit_wallet_id')->nullable();
            $table->foreign('credit_wallet_id')->references('id')->on('wallets');
            $table->unsignedBigInteger('debit_wallet_id')->nullable();
            $table->foreign('debit_wallet_id')->references('id')->on('wallets'); 
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
        Schema::dropIfExists('transactions');
    }
}
