<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormAccessKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_access_keys', function (Blueprint $table) {
            $table->id();
            $table->text('access_key');
            $table->text('domains')->nullable()->comment("This column contain comma seperated string of domains. This key is restricted to this domains only");
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
        Schema::dropIfExists('form_access_keys');
    }
}
