<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Race;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class RegistrationController extends Controller
{
    /**
     * Display a listing of registrations with optional filters.
     *
     * This method handles the incoming request, retrieves the specified filters 
     * from the request, fetches the relevant registrations using the 
     * getRegistrations method, and renders the Registrations/Index view with 
     * the filtered registrations, available races, and predefined categories.
     *
     * @param Request $request The incoming HTTP request containing filter parameters.
     *
     * @return InertiaResponse An Inertia response rendering the Registrations/Index view.
     */
    public function index(Request $request): InertiaResponse
    {
        $filters = $request->only(['race', 'gender', 'category', 'search']);

        $categories = [
            ['id' => 'general', 'name' => 'General'],
            ['id' => 'master', 'name' => 'Master'],
        ];

        $registrations = $this->getRegistrations($filters);

        return Inertia::render('Registrations/Index', [
            'registrations' => $registrations,
            'filters' => $filters,
            'races' => Race::all(['id', 'name']),
            'categories' => $categories
        ]);
    }

    /**
     * Retrieve a paginated list of registrations based on the provided filters.
     *
     * This function queries the Registration model, applies the specified filters,
     * eager loads the related participant and race models, orders the results by 
     * descending registration ID, and paginates the results to 10 per page.
     *
     * @param array $filters An associative array of filters to apply to the query.
     *
     * @return LengthAwarePaginator A paginator instance containing the filtered 
     *                              registrations.
     */
    private function getRegistrations(array $filters): LengthAwarePaginator
    {
        return Registration::query()
            ->with(['participant', 'race'])
            ->filter($filters)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();
    }
}
