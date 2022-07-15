<?php


namespace App\Services;


abstract class AbstractWeatherService {

    // Uses for conversion degress to meters and meters to degress
    const DISTANCE_CONVERSION=112370.0227;

    // Grid size for snap coordinates for cache optimization
    const SNAP_GRID_SIZE = 5000;

    /**
     * Helper function for convert meters to degrees
     * @param $meters
     * @return float
     */
    private static function metersToDegrees($meters) {
        return $meters/static::DISTANCE_CONVERSION;
    }


    final public function getWeather($lat, $lng) {
        $gridSize = static::metersToDegrees(static::SNAP_GRID_SIZE); // Meters

        // Snap to grid for optimize a cache
        $snapLat = (floor(($lat / $gridSize)) * $gridSize) + $gridSize / 2;
        $snapLng = (floor(($lng / $gridSize)) * $gridSize) + $gridSize / 2;
    }

}
