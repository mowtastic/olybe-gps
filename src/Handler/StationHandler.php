<?php

namespace App\Handler;

use App\Entity\Station;
use App\Repository\StationRepository;
use App\Service\VelibCoordinates;

class StationHandler
{
    /**
     * @var StationRepository $stationRepository
     */
    private $stationRepository;

    /**
     * @var VelibCoordinates $velibCoordinates
     */
    private $velibCoordinates;

    /**
     * StationHandler constructor.
     *
     * @param StationRepository $stationRepository
     * @param VelibCoordinates  $velibCoordinates
     */
    public function __construct(StationRepository $stationRepository, VelibCoordinates $velibCoordinates)
    {
        $this->stationRepository = $stationRepository;
        $this->velibCoordinates = $velibCoordinates;
    }


    /**
     * @param string $geoLoc
     * @param int    $nbRows
     *
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function insertStationFromApi(string $geoLoc, int $nbRows = 5)
    {
        $insertedStations = [];
        // Call from APi
        $records = $this->velibCoordinates->getContentFromOpenAPI($geoLoc, $nbRows);
        if ($records !== null) {
            // Formatted from data called
            $externalStations = $this->getFormattedStations($records);
            // Persist Data
            foreach($externalStations as $externalStation) {
                $station = new Station();
                $station
                    ->setName($externalStation['name'])
                    ->setDistance($externalStation['dist'])
                    ->setCoordinates($externalStation['coordinates'])
                ;

                $this->stationRepository->persist($station);
            }

            $this->stationRepository->flush();
            $insertedStations = $externalStations;
        }

        return $insertedStations;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function getFormattedStations(array $data)
    {
        $stations = [];
        if (isset($data)) {
            foreach ($data['records'] as $record) {
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