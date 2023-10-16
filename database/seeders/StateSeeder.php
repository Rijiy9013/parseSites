<?php

namespace Database\Seeders;

use App\Models\States;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        States::create(['name' => 'New']);
        States::create(['name' => 'Used']);
        States::create(['name' => 'Open box']);
    }
}
