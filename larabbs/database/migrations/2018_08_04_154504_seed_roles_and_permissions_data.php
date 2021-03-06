<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 先创建权限
        Permission::create(['name' => 'manage_contents']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'edit_settings']);

        // 创建站长角色, 并赋予权限
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');

        // 创建管理员权限
        $manager = Role::create(['name' => 'Maintainer']);
        $manager->givePermissionTo('manage_contents');
    }

    /**
     * FIXME 这段down中的语句着实不能理解,再观察下?
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清楚缓存
        app()['cache']->forget('spatie.permission.cache');

        // FIXME 这段什么意思???
        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard(); // 这里是新建的时候,填充用的?? 不知道为什么把这句加上了?
        \DB::table($tableNames['role_has_permissions'])->delete();
        \DB::table($tableNames['model_has_roles'])->delete();
        \DB::table($tableNames['model_has_permissions'])->delete();
        \DB::table($tableNames['roles'])->delete();
        \DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
