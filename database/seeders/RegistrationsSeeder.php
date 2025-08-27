<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Participant;
use App\Models\Race;
use App\Models\Registration;

class RegistrationsSeeder extends Seeder
{
    public function run(): void
    {
        Race::factory()->count(2)->create(); // 2 races: 5K and 10K
        Participant::factory()->count(50)->create()->each(function ($participant) {
            Registration::factory()->create([
                'participant_id' => $participant->id,
                'race_id' => Race::inRandomOrder()->first()->id,
            ]);
        });
    }
}
