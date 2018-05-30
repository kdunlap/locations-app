<?php

namespace App\Http\Controllers;

use App\Services\CityService;

class LocationController extends Controller
{
    /**
     * @return string
     */
    public function index(){

        return 'add `/{state-slug}/{city-slug}` to the URL above';
    }

    /**
     * @param $state_slug
     * @param $city_slug
     * @param CityService $service
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($state_slug, $city_slug, CityService $service){

        $center_city = $service->getCityByStateSlugAndCitySlug($state_slug, $city_slug);
        if( !$center_city ) abort( 404 );

        $all_cities = $service->getNearbyLocations($center_city->latitude, $center_city->longitude, 30, 60);
        $spread_cities = $service->getSpreadLocations($all_cities, 30);

        return view( 'locations.show', [
            'center_city' => $center_city,
            'spread_cities' => $spread_cities,
            'all_cities' => $all_cities,
        ]);
    }
}
