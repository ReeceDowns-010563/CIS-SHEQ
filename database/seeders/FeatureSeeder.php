<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;
use App\Models\Role;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the feature records exist
        $settings = Feature::updateOrCreate(
            ['key' => 'settings'],
            ['name' => 'Settings', 'description' => 'Admin settings area']
        );

        $complaints = Feature::updateOrCreate(
            ['key' => 'complaints'],
            ['name' => 'Complaints', 'description' => 'Complaint management']
        );

        // Fetch roles by name (avoid hardcoding IDs if possible)
        $admin = Role::where('name', 'admin')->first();
        $basic = Role::where('name', 'basic')->first();

        // Assign: settings => only admin
        if ($settings && $admin) {
            $settings->roles()->sync([$admin->id]);
        }

        // Assign: complaints => admin + basic (if they exist)
        $complaintRoleIds = [];
        if ($admin) {
            $complaintRoleIds[] = $admin->id;
        }
        if ($basic) {
            $complaintRoleIds[] = $basic->id;
        }
        if ($complaints) {
            $complaints->roles()->sync($complaintRoleIds);
        }
    }
}
