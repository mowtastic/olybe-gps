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
    public function testGetContentFromOpenAPI()
    {
        $client = HttpClient::create();
        $velibCoordinates = new VelibCoordinates($client);

        $result = $velibCoordinates->getContentFromOpenAPI('48.8350927878,2.35346813513', 5);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('records', $result);
    }
}