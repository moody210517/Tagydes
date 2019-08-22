<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name', 100);
            $table->string('address_1', 100)->nullable()->default(null);
            $table->string('address_2', 100)->nullable()->default(null);
            $table->string('city', 100)->nullable()->default(null);
            $table->string('nif', 15);
            $table->unsignedInteger('country');
            // $table->integer('state')->nullable()->default(null);
            $table->string('postal_code', 15)->nullable()->default(null);
            // Every not null main_onffice means a branch_office
            $table->unsignedInteger('reseller'); 
            $table->string('status', 20)->index();
            //$table->tinyInteger('created_by')->default('0');
            $table->timestamps();

            $table->foreign('country')->references('id')->on('countries')->onDelete('cascade');

            $table->foreign('reseller')->references('id')->on('resellers')->onDelete('cascade');
        });

        DB::statement("ALTER TABLE customers AUTO_INCREMENT = 310000;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
