<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymeTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payme_transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("transaction_id");
            $table->bigInteger('user_id')->unsigned();
           // $table->foreign("user_id")->references("id")->on("tg_bot_users")->onDelete('cascade')->onUpdate("cascade");
            $table->string("phone", 13);
            $table->integer("amount");
            $table->enum("status", ["pending", "transferred", "cancelled"])->default("pending");
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
        Schema::dropIfExists('payme_transfers');
    }
}
