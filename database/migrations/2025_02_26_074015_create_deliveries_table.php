<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('notice_type_id')->nullable();
            $table->foreignId('project_id')->constrained('project_offices')->nullable()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('beneficiary_id')->constrained('beneficiaries')->nullable()->onDelete('restrict')->onUpdate('cascade');
            $table->string('photo');
            $table->timestamp('date_captured');
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
        Schema::dropIfExists('deliveries');
    }
}
