<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\City;

class LocationController extends Controller
{
    public function states($countryId)
    {
        $states = State::where('country_id', $countryId)->orderBy('name')->get();

        return view('ajax.states', compact('states'));
    }

    public function cities($stateId)
    {
        $cities = City::where('state_id', $stateId)->orderBy('name')->get();

        return view('ajax.cities', compact('cities'));
    }
}
