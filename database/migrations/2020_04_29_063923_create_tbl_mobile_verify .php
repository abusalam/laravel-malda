<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblMobileVerify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_mobile_verify', function (Blueprint $table) {
            $table->increments('code');
            $table->string('mobile_no','20')->collate('latin1_swedish_ci');
            $table->string('otp','30')->collate('latin1_swedish_ci');
            $table->integer('status_otp')->default(0);
            $table->timestamp('otp_creation_time',0)->nullable();
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
        Schema::dropIfExists('tbl_mobile_verify');
    }
}
