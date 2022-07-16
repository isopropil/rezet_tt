<?php


namespace App\Services;


use Illuminate\Support\Facades\Cache;

abstract class AbstractWeatherService {

    // Uses for conversion degress to meters and meters to degress
    const DISTANCE_CONVERSION = 112370.0227;

    // Grid size for snap coordinates for cache optimization (in meters)
    const SNAP_GRID_SIZE = 5000;

    // Cache time in seconds for grid item
    const CACHE_TIME = 3600;

    protected $rawData = null;

    /**
     * Helper function for convert meters to degrees
     * @param $meters
     * @return float
     */
    public static function metersToDegrees($meters) {
        return $meters/static::DISTANCE_CONVERSION;
    }

    /**
     * Simple snap to grid (for cache optimization)
     * @param $lat
     * @param $lng
     * @param $gridSize
     * @return array [$lat, $lng]
     */
    public static function snapToGrid($lat, $lng, $gridSize) {
        $snapLat = (floor(($lat / $gridSize)) * $gridSize) + $gridSize / 2;
        $snapLng = (floor(($lng / $gridSize)) * $gridSize) + $gridSize / 2;

        return [$snapLat, $snapLng];
    }

    /**
     * Request RAW data from weather provider
     * @param $lat
     * @param $lng
     * @return mixed
     */
    abstract protected function requestRawData($lat, $lng);

    final public function getWeather($lat, $lng) {
        [$snapLat, $snapLng] = static::snapToGrid(
            $lat,
            $lng,
            static::metersToDegrees(static::SNAP_GRID_SIZE)
        );

        $cacheKey = static::class.'::'.sha1($snapLat.'::'.$snapLng);
        $this->rawData = Cache::remember($cacheKey, static::CACHE_TIME, function() use ($snapLat, $snapLng) {
            return $this->requestRawData($snapLat, $snapLng);
        });
        return $this;
    }

}
