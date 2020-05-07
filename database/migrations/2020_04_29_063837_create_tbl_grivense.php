<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblGrivense extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tbl_grivense',
            function (Blueprint $table) {
                $table->increments('code');
                $table->string('name', '20')->collate('latin1_swedish_ci');
                $table->string('mobile_no', '20')->collate('latin1_swedish_ci');
                $table->string('email', '30')->collate('latin1_swedish_ci');
                $table->longText('complain')->collate('latin1_swedish_ci');
                $table->string('remark', '300')->nullable()->collate('latin1_swedish_ci');
                $table->integer('close_status')->default(0)->comment('0-> new, 1->close,2->resolve,3->forward');
                $table->string('griev_auto_id', '50')->collate('latin1_swedish_ci');
                $table->string('attatchment', '300')->nullable();

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
        Schema::dropIfExists('tbl_grivense');
    }
}
