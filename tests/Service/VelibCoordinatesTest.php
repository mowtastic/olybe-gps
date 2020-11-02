<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\VelibCoordinates;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class VelibCoordinatesTest
 *
 * @package App\Tests\Service
 */
class VelibCoordinatesTest extends TestCase
{
    public function testGetClosestStations()
    {
        $client = HttpClient::create();
        $velibCoordinates = new VelibCoordinates($client);

        $result = $velibCoordinates->getClosestStations('48.8350927878,2.35346813513', 5);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('name', $result[0]);
        $this->assertArrayHasKey('coordinates', $result[0]);
        $this->assertArrayHasKey('dist', $result[0]);
        $this->assertEquals('Le Brun - Gobelins', $result[0]['name']);
        $this->assertEquals('0.0', $result[0]['dist']);
    }
}