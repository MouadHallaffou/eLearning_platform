<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-courses',
            'edit-courses',
            'delete-courses',
            'view-courses',
            'enroll-in-courses',
            'manage-enrollments',
            'add-videos',
            'edit-videos',
            'delete-videos',
            'view-videos',
            'manage-categories',
            'manage-tags',
            'manage-users',
            'manage-roles',
            'view-statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Créer les rôles et assigner les permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $mentorRole = Role::create(['name' => 'mentor']);
        $mentorRole->givePermissionTo([
            'create-courses',
            'edit-courses',
            'delete-courses',
            'view-courses',
            'manage-enrollments',
            'add-videos',
            'edit-videos',
            'delete-videos',
            'view-videos',
            'view-statistics',
        ]);

        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view-courses',
            'enroll-in-courses',
            'view-videos',
        ]);

        // Le rôle "visiteur" n'a pas besoin de permissions spécifiques
        Role::create(['name' => 'visitor']);
    }
}
