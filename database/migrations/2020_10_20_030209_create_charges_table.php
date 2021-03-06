<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->bigincrements('charges_id');
            
            $table->unsignedBigInteger('invoice_id')->nullable();

            $table->unsignedBigInteger('house_id');
            $table->foreign('house_id')->references('house_id')->on('houses');
            $table->unsignedBigInteger('service_id')->nullable();

            $table->bigInteger('amount')->nullable();
            $table->string('charge_date')->nullable();
            $table->string('status')->nullable();
            $table->string('charge_month')->nullable();
            $table->string('description_charge')->nullable();
            

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
        Schema::dropIfExists('charges');
    }
}
