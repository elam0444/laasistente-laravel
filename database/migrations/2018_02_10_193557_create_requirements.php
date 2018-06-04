<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Createrequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('activity')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('user_id');

            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->string('address');
            $table->string('description');
            $table->string('zip_code')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::create('service_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->text('description');
        });

        DB::table('service_categories')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'Errands',
                    'slug' => 'errands',
                    'description' => '',
                ],
                [
                    'id' => 2,
                    'name' => 'Cleaning',
                    'slug' => 'cleaning',
                    'description' => '',
                ],
                [
                    'id' => 3,
                    'name' => 'Common Services',
                    'slug' => 'common',
                    'description' => '',
                ],
                [
                    'id' => 4,
                    'name' => 'Specialized Profesional Services',
                    'slug' => 'specialized_profesional',
                    'description' => '',
                ],
                [
                    'id' => 5,
                    'name' => 'Other',
                    'slug' => 'other',
                    'description' => '',
                ]
            ]
        );

        Schema::create('requirement_service_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
        });

        DB::table('requirement_service_status')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'requested',
                    'slug' => 'requested',
                ],
                [
                    'id' => 2,
                    'name' => 'queued',
                    'slug' => 'queued',
                ],
                [
                    'id' => 3,
                    'name' => 'ready-for-deliver',
                    'slug' => 'ready-for-deliver',
                ],
                [
                    'id' => 4,
                    'name' => 'in-progress',
                    'slug' => 'in-progress',
                ],
                [
                    'id' => 5,
                    'name' => 'finished',
                    'slug' => 'finished',
                ],
                [
                    'id' => 6,
                    'name' => 'declined',
                    'slug' => 'declined',
                ],
            ]
        );

        Schema::create('transaction_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
        });

        DB::table('transaction_status')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'queued',
                    'slug' => 'queued',
                ],
                [
                    'id' => 2,
                    'name' => 'approved',
                    'slug' => 'approved',
                ],
                [
                    'id' => 3,
                    'name' => 'declined',
                    'slug' => 'declined',
                ]
            ]
        );

        Schema::create('requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('address_id');
            $table->unsignedInteger('additional_address_id')->nullable();
            $table->text('description');

            $table->tinyInteger('is_accepted')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('address_id')->references('id')->on('addresses');
        });

        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('service_category_id');
            $table->string('name');
            $table->text('description');
            $table->string('units');
            $table->integer('units_min');
            $table->decimal('cost_per_unit', 10, 2);

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('service_category_id')->references('id')->on('service_categories');
        });

        Schema::create('requirement_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('requirement_id');
            $table->unsignedInteger('service_id');
            $table->unsignedInteger('associate_user_id')->nullable();
            $table->unsignedInteger('requirement_service_status_id');
            $table->dateTime('delivery_date_time');
            $table->integer('qty');
            $table->decimal('total_cost', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('requirement_id')->references('id')->on('requirements');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('associate_user_id')->references('id')->on('users');
            $table->foreign('requirement_service_status_id')->references('id')->on('requirement_service_status');
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('requirement_service_id');
            $table->string('description');
            $table->decimal('total', 10, 2);
            $table->unsignedInteger('transaction_status_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('requirement_service_id')->references('id')->on('requirement_services');
            $table->foreign('transaction_status_id')->references('id')->on('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
        Schema::drop('transaction_status');
        Schema::drop('requirement_services');
        Schema::drop('requirement_service_status');
        Schema::drop('requirements');
        Schema::drop('services');
        Schema::drop('service_categories');
        Schema::drop('addresses');
        Schema::drop('companies');
    }
}
