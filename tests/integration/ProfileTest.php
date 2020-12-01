<?php

declare(strict_types=1);

namespace Tests\integration;

class ProfileTest extends BaseTestCase
{
    public function testGetProfiles()
    {
        $response = $this->runApp('GET', '/profiles');
        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('images', $result);
        
        $resultsArray = (array)json_decode($value, true)["message"];

        // Check 7 results
        $this->assertCount(7, $resultsArray);

        // Check just males
        $this->assertStringNotContainsString('female', $result);
        $this->assertStringNotContainsString('other', $result);

        // Check closest location and most popular result is top
        $this->assertEquals("Alex Smith", $resultsArray["1"]["name"]);
        
        // Check second in list is near but lower scoring, it check results are ordered by location first and then popularity
        $this->assertEquals("Bob Smith", $resultsArray["2"]["name"]);
        
        // Check furthest away and least popular is bottom
        $this->assertEquals("George Smith", $resultsArray["7"]["name"]);

        $this->assertStringNotContainsString('error', $result);
    }

    public function testGetProfilesUnauthenticated()
    {
        $response = $this->runApp('GET', '/profiles', null, false, true);
        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('error', $result);
        $this->assertStringContainsString('JWT Token required.', $result);
    }

    public function testAgeFiltering()
    {
        $response = $this->runApp('GET', '/profiles?minAge=45&maxAge=70');
        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('images', $result);
        
        $resultsArray = (array)json_decode($value, true)["message"];

        // Check only 3 results
        $this->assertCount(3, $resultsArray);

        // Check expected top result 
        $this->assertEquals("Charlie Smith", $resultsArray["3"]["name"]);
        
        // Check expected bottom result 
        $this->assertEquals("Fred Smith", $resultsArray["6"]["name"]);

        $this->assertStringNotContainsString('error', $result);
    }

    public function testGenderFiltering()
    {
        $response = $this->runApp('GET', '/profiles?gender=male,other');
        $result = (string) $response->getBody();
        $value = json_encode(json_decode($result));

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringContainsString('images', $result);
        
        $resultsArray = (array)json_decode($value, true)["message"];


        // Check only 3 results
        $this->assertCount(10, $resultsArray);

        $this->assertStringNotContainsString('female', $result);
        $this->assertStringContainsString('other', $result);

        // Check expected top result 
        $this->assertEquals("Alex Smith", $resultsArray["1"]["name"]);
        
        // Check an expected "other" result
        $this->assertEquals("Mrs. Enola Kihn Sr.", $resultsArray["8"]["name"]);

        $this->assertStringNotContainsString('error', $result);
    }
}
