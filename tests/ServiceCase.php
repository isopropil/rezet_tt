<?php

namespace Tests;

use App\Models\User;
use App\Services\AbstractWeatherService;
use Illuminate\Support\Facades\Auth;

class ServiceCase extends TestCase {

    /**
     * Simple test snap to grid functionality
     */
    public function testCoordsOptimization() {
        $startLat = 51.388260509802;
        $startLng = 30.101444484268;
        [$fLat, $fLng] =
            AbstractWeatherService::snapToGrid($startLat, $startLng, AbstractWeatherService::metersToDegrees(1000));

        $startLat = $startLat + AbstractWeatherService::metersToDegrees(200);
        $startLng = $startLng + AbstractWeatherService::metersToDegrees(200);

        [$cLat, $cLng] =
            AbstractWeatherService::snapToGrid($startLat, $startLng, AbstractWeatherService::metersToDegrees(1000));

        $this->assertEquals($fLat, $cLat);
        $this->assertEquals($fLng, $cLng);

        $startLat = $startLat + AbstractWeatherService::metersToDegrees(1200);
        $startLng = $startLng + AbstractWeatherService::metersToDegrees(1200);
        [$cLat, $cLng] =
            AbstractWeatherService::snapToGrid($startLat, $startLng, AbstractWeatherService::metersToDegrees(1000));

        $this->assertNotEquals($fLat, $cLat);
        $this->assertNotEquals($fLng, $cLng);
    }

    /**
     * Test weather data requesting
     */
    public function testGetWeatherEndpoint() {
        // Test unauthorized access
        $res = $this->get('/api/weather');
        $this->assertEquals(403, $res->getStatusCode());

        $user = new User(['id' => 1]);

        // Check authorized access without coordinates
        $res = $this->actingAs($user)->get('/api/weather');
        $this->assertEquals(200, $res->getStatusCode());
        $body = $res->json();
        $this->assertEquals('error', $body['status']);

        $res = $this->actingAs($user)->get('/api/weather?lat=50.1&lng=30.1');
        $this->assertEquals(200, $res->getStatusCode());
        $body = $res->json();
        $this->assertEquals('ok', $body['status']);
        $this->assertEquals(true, is_array($body['payload']));
        $this->assertEquals(false, empty($body['payload']['temp']));
    }

}
