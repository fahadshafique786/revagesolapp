<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('app_details_id');
            $table->string('package_id')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('stream_key')->nullable();
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
        Schema::dropIfExists('app_credentials');
    }
}
