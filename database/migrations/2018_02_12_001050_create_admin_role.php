<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roleClerk = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'User - Clerk',
            'slug' => 'clerk'
        ]);
        $roleClerk->save();

        $roleAssociate = Sentinel::getRoleRepository()->createModel()->create([
            'name' => 'User - Associate',
            'slug' => 'associate'
        ]);
        $roleAssociate->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roleClerk = Sentinel::findRoleBySlug('clerk');
        $roleClerk->delete();

        $roleAssociate = Sentinel::findRoleBySlug('associate');
        $roleAssociate->delete();
    }
}
