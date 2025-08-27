<?php

use App\Models\Registration;
use App\Models\Participant;
use App\Models\Race;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the registrations index page with pagination', function () {
    Registration::factory()->count(12)->create();

    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('registrations.index'));

    $response->assertStatus(200)
             ->assertInertia(fn (Assert $page) => $page
                 ->component('Registrations/Index')
                 ->has('registrations.data', 10)
                 ->has('registrations.links')
             );
});

it('filters registrations by category', function () {
    Registration::factory()->count(5)->create(['category' => 'general']);
    Registration::factory()->count(7)->create(['category' => 'master']);

    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('registrations.index', ['category' => 'general']));

    $response->assertStatus(200)
             ->assertInertia(fn (Assert $page) => $page
                 ->component('Registrations/Index')
                 ->has('registrations.data', 5)
             );
});
