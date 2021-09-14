<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('form_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('access_key');
            $table->foreign('access_key')->references('id')->on('form_access_keys')->onDelete('cascade');
            $table->text('email');
            $table->text('confirmation_mail');
            $table->text('honeypot')->nullable();
            $table->enum('status',['0','1'])->comment("0=>Fail, 1=>Success")->nullable();
            $table->json('full_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_messages');
    }
}
