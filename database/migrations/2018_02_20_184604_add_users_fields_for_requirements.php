<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersFieldsForRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gender', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        DB::table('gender')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'female',
                ],
                [
                    'id' => 2,
                    'name' => 'male',
                ],
                [
                    'id' => 3,
                    'name' => 'other',
                ]
            ]
        );



        Schema::table('users', function (Blueprint $table) {
            $table->smallInteger('gender_id');
            $table->unsignedInteger('company_id')->nullable();
            $table->string('phone_1');
            $table->string('phone_2')->nullable();
            $table->smallInteger('has_mobile_app')->default(0);
            $table->string('social_login')->nullable();

            $table->foreign('gender_id')->references('id')->on('gender');
            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone_1', 'phone_2', 'has_mobile_app', 'gender_id', 'company_id',  'social_login']);
        });

        Schema::drop('gender');
    }
}
