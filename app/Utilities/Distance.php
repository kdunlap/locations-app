<?php

namespace App\Utilities;

class Distance{

    /**
     * @param $latitude1
     * @param $longitude1
     * @param $latitude2
     * @param $longitude2
     * @return int
     */
    public static function haversineGreatCircleDistance($latitude1, $longitude1, $latitude2, $longitude2) {

//            return ( 6371 * 3.1415926 * sqrt( ( $latitude1 - $latitude2 ) * ( $latitude1 - $latitude2 ) + cos( $latitude1 / 57.29578 ) * cos( $latitude2 / 57.29578 ) * ( $longitude1 - $longitude2 ) * ( $longitude1 - $longitude2 ) ) / 180 );

        $earth_radius = 6371;

        $dLat = deg2rad($latitude2 - $latitude1);
        $dLon = deg2rad($longitude2 - $longitude1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        return $d;
    }
}