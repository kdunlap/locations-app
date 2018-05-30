<?php

namespace App\Services;

use App\Models\City;
use App\Utilities\Distance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Skilla\MaximalCliques\lib\BronKerboschAlgorithms;

class CityService
{
    /**
     * @var BronKerboschAlgorithms
     */
    protected $algorithm;

    /**
     * LocationService constructor.
     *
     * @param BronKerboschAlgorithms $algorithm
     */
    public function __construct(BronKerboschAlgorithms $algorithm)
    {
        $this->algorithm = $algorithm;
    }

    /**
     * @param $state_slug
     * @param $city_slug
     * @return City | null
     */
    public function getCityByStateSlugAndCitySlug($state_slug, $city_slug)
    {
        $key = 'city.get.' . $state_slug . '.' . $city_slug;

        return Cache::remember( $key, 1440, function() use ($state_slug, $city_slug)
        {
            return City::whereHas('state', function($query) use ($state_slug)
                {
                    return $query->where('slug', '=', $state_slug);
                })
                ->where('slug', '=', $city_slug)
                ->first();
        });
    }

    /**
     * @param $latitude
     * @param $longitude
     * @param int $inner_radius
     * @param int $outer_radius
     * @return Collection | null
     */
    public function getNearbyLocations($latitude, $longitude, $inner_radius = 30, $outer_radius = 50)
    {
        $haversine_sql = '( 6371 * acos( cos( radians( ? ) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians( ? ) ) + sin( radians( ? ) ) * sin( radians( latitude ) ) ) )';

        // Haversine distance formula in km
        return City::
            // Add a rough bounding box to limit the number of distance calculations performed
            // This is not perfect by any means, but gives the desired results much quicker
            // Latitude: 1 deg = 110.574 km
            // Longitude: 1 deg = 111.320*cos(latitude) km
              where('latitude', '>', $latitude - 1)
            ->where('latitude', '<', $latitude + 1)
            ->where('longitude', '>', $longitude - 1)
            ->where('longitude', '<', $longitude + 1)
            ->whereRaw($haversine_sql . '>' . $inner_radius, [ $latitude, $longitude, $latitude ])
            ->whereRaw($haversine_sql . '<' . $outer_radius, [ $latitude, $longitude, $latitude ])
            ->orderBy( 'population', 'desc' )
//            ->orderBy('distance')
            ->with('state')
            ->limit(20)
            ->get();
    }

    /**
     * @param $cities
     * @param $distance_between
     * @return Collection
     */
    public function getSpreadLocations($cities, $distance_between)
    {
        // Loop through locations and calculate the distances between them
        $distances = [];
        foreach ($cities as $city1) {
            foreach ($cities as $city2) {

                if ($city1->id == $city2->id) $distances[$city1->id][$city2->id] = null;

                // already calculated the reverse of the distance
                if (isset($distances[$city1->id][$city2->id])) continue;

                $distance = Distance::haversineGreatCircleDistance($city1->latitude, $city1->longitude, $city2->latitude, $city2->longitude);

                if ($distance > $distance_between) {
                    $distances[$city1->id][$city2->id] = round($distance, 2);
                    $distances[$city2->id][$city1->id] = round($distance, 2);
                } else $distances[$city1->id][$city2->id] = null;
            }
        }

        $final_indexes = [];
        if (count($distances)) {
            $this->algorithm->setRVector([]);
            $this->algorithm->setPVector(array_keys(current($distances)));
            $this->algorithm->setXVector([]);
            $this->algorithm->setNVector($distances);

            $result = $this->algorithm->obtainCompleteGraphsWithVertexOrderingForVertex($cities->first()->id);

            if (count($result) > 0) {
                $final_indexes = $this->algorithm->retrieveMaximalClique();
            }
        }

       return $cities->filter(function ($city) use ($final_indexes) {
            return in_array($city->id, $final_indexes);
        });
    }
}