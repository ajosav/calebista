<?php

namespace App\Http\Controllers\API\Common;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\JsonResponse;

class CountryStateController extends Controller
{
    /**
     * listCountries
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function listCountries() : JsonResponse
    {
        return response()->success('Successful', Country::all());
    }

    /**
     * listStates
     *
     * @return Illuminate\Http\JsonResponse
     */
    public function listStates() : JsonResponse
    {
        $states = State::when(request('country_code'), fn ($states) => $states->whereRelation('country', 'code', '=', request('country_code')))->get();
        return response()->success('Successful', $states);
    }
}
