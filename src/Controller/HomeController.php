<?php

namespace App\Controller;

use App\Entity\Itinerary;
use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(
        PlaceRepository $placeRepository,
        Request $request,
        SessionInterface $session): Response
    {
        // Because it is only our selection, I decided to choose the first 3 places, but the choice isn't important
        $selectionPlaces = $placeRepository->findBy(['id' => [1, 2, 3]]);

        // Get the user's location with the help of the session
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            if (isset($data['latitude']) && isset($data['longitude'])) {
                $latitude = $data['latitude'];
                $longitude = $data['longitude'];
                $session->set('user_latitude', $latitude);
                $session->set('user_longitude', $longitude);
            }
        }

        // Get the user's location from the session
        $latitude = $session->get('user_latitude');
        $longitude = $session->get('user_longitude');

        $nearbyPlaces = [];
        if ($latitude && $longitude) {
            $nearbyPlaces = $placeRepository->findNearbyPlaces($latitude, $longitude);
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('nearby_places_list.html.twig', [
                'places' => $nearbyPlaces,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'selection_places' => $selectionPlaces,
            'nearby_places' => $nearbyPlaces,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);
    }

    #[Route('/', name: 'app_redirect_home')]
    public function redirectToHome(): RedirectResponse
    {
        return $this->redirectToRoute('app_home');
    }

    #[Route('/calculer', name: 'app_calculer')]
    public function calculate(EntityManagerInterface $manager): Response
    {
        $places = $manager->getRepository(Place::class)->findAll();
        foreach ($places as $place) {
            $total = 0;
            $nbNote = 0;
            foreach ($place->getNotes() as $note) {
                ++$nbNote;
                $total += $note->getNote();
            }
            $place->setNote($total / $nbNote);
            $manager->flush();
        }

        $itineraries = $manager->getRepository(Itinerary::class)->findAll();
        foreach ($itineraries as $itinerary) {
            $distance = 0;
            $latPre = 0; // lgntude du lieux d'avant
            $lngPre = 0; // lattitude du lieux d'avant
            $time = 0;
            $total = 0;
            $nbNote = 0;
            foreach ($itinerary->getPathOrder() as $pathOrder) {
                $place = $pathOrder->getPlace();
                $time += $place->getVisitTime();
                ++$nbNote;
                $total += $place->getNote();
                if (0 != $latPre && 0 != $lngPre) {
                    $pi80 = M_PI / 180;
                    $lat1 = $latPre * $pi80;
                    $lng1 = $lngPre * $pi80;
                    $lat2 = $place->getLatitude() * $pi80;
                    $lng2 = $place->getLongitude() * $pi80;

                    $r = 6372.797; // rayon moyen de la Terre en km
                    $dlat = $lat2 - $lat1;
                    $dlng = $lng2 - $lng1;
                    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2)
                        * sin($dlng / 2) * sin($dlng / 2);
                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                    $km = $r * $c;

                    $distance += $km * 1.2;
                }
                $latPre = $place->getLatitude();
                $lngPre = $place->getLongitude();
            }
            if ($nbNote > 0) {
                $itinerary->setNote(round($total / $nbNote, 2));
            } else {
                $itinerary->setNote(0);
            }
            $itinerary->setTime($time);
            $itinerary->setDistance(round($distance, 2));
            $manager->flush();
        }
        $manager->flush();

        return $this->redirectToRoute('app_home');
    }
}
