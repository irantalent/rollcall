<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayslipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payslips', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('month');
            $table->smallInteger('year');
            $table->integer('total_working_time')->default(0);
            $table->integer('late_in_time')->default(0);
            $table->integer('early_out_time')->default(0);
            $table->integer('overtime_time')->default(0);
            $table->integer('working_on_holiday_time')->default(0);
            $table->integer('basic_salary')->default(0);
            $table->integer('housing_allowance')->default(0);
            $table->integer('food_coupon')->default(0);
            $table->integer('child_allowance')->default(0);
            $table->integer('responsibility')->default(0);
            $table->integer('transport')->default(0);
            $table->integer('bonus')->default(0);
            $table->integer('overtime')->default(0);
            $table->integer('working_on_holidays')->default(0);
            $table->integer('commission')->default(0);
            $table->integer('annual_commission')->default(0);
            $table->integer('total_salary')->default(0);
            $table->integer('subject_to_sso')->default(0);
            $table->integer('tax_exemption')->default(0);
            $table->integer('subject_to_tax')->default(0);
            $table->integer('social_security')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('supplementary_insurance')->default(0);
            $table->integer('late_in')->default(0);
            $table->integer('early_out')->default(0);
            $table->integer('advance')->default(0);
            $table->integer('leave')->default(0);
            $table->integer('unused_leave')->default(0);
            $table->integer('net_payable')->default(0);
            $table->text('description')->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payslips');
    }
}
