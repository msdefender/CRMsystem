<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agreement_id');
            $table->foreign("agreement_id")->references("id")->on("agreements")->onUpdate("cascade")->onDelete("cascade");
            $table->unsignedBigInteger('customer_id');
            $table->foreign("customer_id")->references("id")->on("customers")->onUpdate("cascade")->onDelete("cascade");
            $table->string('address')->default(0);
            $table->string('status')->default(0);
            $table->string('payments')->default(0);
            $table->string('percentage')->default(0);
            $table->string('phone')->default(0);
            $table->string('is_confirmed')->default(0);
            $table->string('has_agreements')->default(0);
           
            $table->date('dead_line');
            $table->date('start_date');
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
        Schema::dropIfExists('orders');
    }
}
