<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblGrievenceForwored extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tbl_grievence_forwored', function (Blueprint $table) {
                $table->increments('code');
                $table->integer('griv_code');
                $table->integer('to_forword')->nullable();
                $table->integer('from_forword');
                $table->string('remark', '300')->collate('latin1_swedish_ci');
                $table->string('attatchment', '300')->collate('latin1_swedish_ci')->nullable();
                $table->integer('status')->default(0);

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_grievence_forwored');
    }
}
