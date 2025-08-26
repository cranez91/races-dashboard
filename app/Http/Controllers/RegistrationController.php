<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Race;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['race', 'gender', 'category', 'search']);

        $categories = [
            ['id' => 'general', 'name' => 'General'],
            ['id' => 'master', 'name' => 'Master'],
        ];

        $registrations = Registration::query()
            ->with(['participant', 'race'])
            ->filter($filters)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Registrations/Index', [
            'registrations' => $registrations,
            'filters' => $filters,
            'races' => Race::all(['id', 'name']),
            'categories' => $categories
        ]);
    }
}
