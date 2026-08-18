<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserOperator extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Operator Shawarma',
            'email' => 'operator@shawarma.com',
            'password' => 'operatorSS123!',
            'role' => 'operator',
        ]);
    }
}
