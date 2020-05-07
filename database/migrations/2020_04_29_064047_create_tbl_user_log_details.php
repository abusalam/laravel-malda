<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblUserLogDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_user_log_details', function (Blueprint $table) {
            $table->increments('code')->unsigned();
            $table->integer('userCode');
            $table->string('sessionId','100')->collate('utf8mb4_unicode_ci');
            $table->string('userIp','100')->collate('utf8mb4_unicode_ci');
            $table->string('visitedPage','100')->collate('utf8mb4_unicode_ci');
            $table->json('description');
            $table->string('browser','255')->collate('utf8mb4_unicode_ci');
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
        Schema::dropIfExists('tbl_user_log_details');
    }
}
