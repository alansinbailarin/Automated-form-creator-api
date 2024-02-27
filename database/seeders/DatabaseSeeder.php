<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(10)->create();
        \App\Models\UserDetail::factory(10)->create();
        \App\Models\Organization::factory(10)->create();
        \App\Models\Office::factory(10)->create();
        \App\Models\Team::factory(4)->create();
        \App\Models\SurveyStatus::factory(3)->create();
        \App\Models\TeamUser::factory(10)->create();
        \App\Models\Survey::factory(200)->create();
        \App\Models\Page::factory(500)->create();
        \App\Models\ElementType::factory(10)->create();
        \App\Models\Element::factory(800)->create();
        \App\Models\Choice::factory(1000)->create();
        \App\Models\Response::factory(1000)->create();
        \App\Models\DetailedResponse::factory(1000)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
