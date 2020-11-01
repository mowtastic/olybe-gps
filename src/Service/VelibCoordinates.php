<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class VelibCoordonates
 *
 * @package App\Service
 */
class VelibCoordinates
{
    /**
     * @var HttpClientInterface $client
     */
    private $client;

    /**
     * VelibCoordonates constructor.
     *
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $geoLoc
     * @param int    $nbRows
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getContentFromOpenAPI(string $geoLoc, int $nbRows = 5) :?array
    {
        $response = $this->client->request(
            'GET',
            'https://opendata.paris.fr/api/records/1.0/search/?dataset=velib-emplacement-des-stations&q=',
            [
                'query' => [
                    'geofilter.distance' => $geoLoc,
                    'rows' => $nbRows
                ]
            ]
        );

        if ($response->getStatusCode() === Response::HTTP_OK) {
            return $response->toArray();
        }

        return null;
    }

    /**
     * @param string $geoLoc
     * @param int    $nbRows
     *
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getClosestStations(string $geoLoc, int $nbRows = 5)
    {
        $stations = [];
        $response = $this->getContentFromOpenAPI($geoLoc, $nbRows);

        if (isset($response)) {
            foreach ($response['records'] as $record) {
                $stations[] = [
                    'name' => $record['fields']['name'],
                    'coordinates' => $record['fields']['coordonnees_geo'][0] . ',' . $record['fields']['coordonnees_geo'][1],
                    'dist' => $record['fields']['dist'],
                ];
            }
        }

        return $stations;
    }
}