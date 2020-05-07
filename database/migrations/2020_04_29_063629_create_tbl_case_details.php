<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblCaseDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'tbl_case_details', function (Blueprint $table) {
                $table->increments('code');
                $table->string('case_no', '11')->collate('latin1_swedish_ci');
                $table->date('nxt_hearing_date');
                $table->string('description', '200')->collate('latin1_swedish_ci');
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
        Schema::dropIfExists('tbl_case_details');
    }
}
