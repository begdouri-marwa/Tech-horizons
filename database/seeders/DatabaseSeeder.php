<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Issue;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create an editor user
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'role' => 'editor',
        ]);

        // Create an unpublished issue with no title or description
        Issue::create([
            'title' => '',
            'description' => '',
            'status' => 'unpublished',
            'published_at' => null,
        ]);
    }
}
