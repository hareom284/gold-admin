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
        Schema::create('subscriptions_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('request_no')->nullable();
            $table->string('order_id')->nullable();
            $table->text('qr_string')->nullable();
            $table->integer('plan_id')->nullable();
            $table->Integer('subscriptions_id')->nullable();
            $table->Integer('user_id')->nullable();
            $table->Double('amount')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('tax_data')->nullable();
            $table->text('other_transactions_details')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->integer('updated_by')->unsigned()->nullable();
            $table->integer('deleted_by')->unsigned()->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('subscriptions_transactions');
    }
};
