<?php

use App\Models\Registration;
use App\Models\Participant;
use App\Models\Race;
use Inertia\Testing\AssertableInertia as Assert;

it('renders the registrations index page with pagination', function () {
    // Crear 12 registros para probar paginación
    Registration::factory()->count(12)->create();

    $response = $this->get(route('registrations.index'));

    $response->assertStatus(200)
             ->assertInertia(fn (Assert $page) => $page
                 ->component('Registrations/Index')
                 ->has('registrations.data', 10) // 10 por página
                 ->has('registrations.links')
             );
});

it('filters registrations by category', function () {
    // Crear registros de cada categoría
    Registration::factory()->count(5)->create(['category' => 'general']);
    Registration::factory()->count(7)->create(['category' => 'master']);

    // Filtrar por categoría "general"
    $response = $this->get(route('registrations.index', ['category' => 'general']));

    $response->assertStatus(200)
             ->assertInertia(fn (Assert $page) => $page
                 ->component('Registrations/Index')
                 ->has('registrations.data', 5)
             );
});
