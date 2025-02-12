<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class MapController extends AbstractController
{
    /**
     * This controller provides access to the application's “/map” resource.
     * It retrieves the GPS coordinates, name, id and description of the different places from the database
     * and transmits them to the “templates/map/index.html.twig” view in the form of a JSON array.
     */
    #[Route('/map', name: 'app_map')]
    public function index(Request $request, PlaceRepository $placeRepository, CategoryRepository $categoryRepository, #[MapQueryParameter('filter')] string $filter = ''): Response
    {
        $places = $placeRepository->findPlaceLocation($filter);
        $categories = $categoryRepository->findBy([], ['name' => 'ASC']);

        // If this route is accessed through the place page, we will get the coordinates of the place
        // so that the map is centered on it.
        $latitude = $request->query->get('latitude');
        $longitude = $request->query->get('longitude');

        return $this->render('map/index.html.twig', [
            'places' => json_encode($places),
            'categories' => $categories,
            'filter' => $filter,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }
}
