<?php

namespace App\Controller;


use App\Service\VelibCoordinates;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StationController extends AbstractController
{
    const NB_STATION = 5;

    /**
     * @param Request $request
     * @param VelibCoordinates $velibCoordinates
     *
     * @return JsonResponse
     * @Route("/api/stations/search", name="app_station_search", methods={"POST"})
     */
    public function search(Request $request, VelibCoordinates $velibCoordinates)
    {
        $coordinates = $request->request->get('coordinates') ?? null;
        $nbRows = $request->request->get('nb_rows') ?? self::NB_STATION;

        if ($coordinates !== null) {
            $stations = $velibCoordinates->getClosestStations($coordinates, $nbRows);

            return new JsonResponse($stations, Response::HTTP_OK);
        }

        return new JsonResponse(['message' => 'Not found'], Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}