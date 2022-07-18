<?php

namespace Tests;

use App\Models\User;
use App\Services\AbstractWeatherService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class ServiceCase extends TestCase {
    use RefreshDatabase;

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

    public function testSignup() {
        $fakeEmail = 'fake223@example.com';
        $res = $this->postJson('api/signup', [
            'email' => $fakeEmail,
            'name' => 'Fake',
            'password' => '12345678'
        ]);
        $this->assertEquals(200, $res->getStatusCode());
        $json = $res->json();
        $this->assertEquals('ok', $json['status']);

        // Register with same email
        $res = $this->postJson('api/signup', [
            'email' => $fakeEmail,
            'name' => 'Fake2',
            'password' => '87654321'
        ]);
        $this->assertEquals(422, $res->getStatusCode());


        // Register invalid email
        $res = $this->postJson('api/signup', [
            'email' => 'invalidEmail',
            'name' => 'Fake2',
            'password' => '87654321'
        ]);
        $this->assertEquals(422, $res->getStatusCode());
    }

    public function testLogin() {
        $res = $this->postJson('api/signup', [
            'email' => 'test@example.com',
            'name' => 'Fake',
            'password' => '87654321'
        ]);


        // Login with valid user
        $res = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '87654321'
        ]);
        $this->assertEquals(200, $res->getStatusCode());
        $res->assertJson(function (AssertableJson $json) {
            return $json->where('status', 'ok')
                ->etc();
        });

        // Log in with invalid user
        $res = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => '111'
        ]);
        $this->assertEquals(200, $res->getStatusCode());
        $res->assertJson(function (AssertableJson $json) {
            return $json->where('status', 'error')
                ->etc();
        });
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
