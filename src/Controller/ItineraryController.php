<?php

namespace App\Controller;

use App\Entity\Itinerary;
use App\Entity\PathOrder;
use App\Form\ItineraryType;
use App\Repository\ItineraryRepository;
use App\Repository\PathOrderRepository;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class ItineraryController extends AbstractController
{
    #[Route('/itinerary', name: 'app_itinerary')]
    public function index(
        ItineraryRepository $itineraryRepository,
        EntityManagerInterface $entityManager,
    ): Response {
        $getuserItineraries = $itineraryRepository->findBy(['user' => $this->getUser()]);
        $userItineraries = [];
        foreach ($getuserItineraries as $itinerary) {
            // The duration of an itinerary is stored in minutes, so we will convert it to HH:MM format
            // before sending it to the view
            if ($itineraryRepository->countPlaceForOne($itinerary->getId()) < 2) {
                $entityManager->remove($itinerary);
                $entityManager->flush();
            } else {
                $itineraryTime = $itinerary->getTime();
                $time = '';

                if ($itineraryTime) {
                    $hours = round($itineraryTime);
                    $minutes = $itineraryTime - $hours / 100 * 60;
                    $nbQuinzaine = round($minutes / 4);

                    // Format the time as HH:MM
                    $time = $hours.'h'.(15 * $nbQuinzaine);
                }

                $distance = $itinerary->getdistance().' km';

                // We will use a default image if the itinerary has no image
                $itineraryImage = $itinerary->getImage();
                $url = '/images/default.jpg';

                if (!empty($itineraryImage)) {
                    // Get the BLOB field of the image
                    $imageData = $itineraryImage->getImage();

                    // If this is a stream, we have to read the content
                    if (is_resource($imageData)) {
                        $imageData = stream_get_contents($imageData);
                    }

                    // Then we convert the string in base64 and save the source in the array that will be sent to the view
                    $url = 'data:image/jpeg;base64,'.base64_encode($imageData);
                }

                $userItineraries[] = [
                    'id' => $itinerary->getId(),
                    'name' => $itinerary->getName(),
                    'description' => $itinerary->getDescription(),
                    'time' => $time,
                    'distance' => $distance,
                    'note' => $itinerary->getNote(),
                    'image' => $url,
                ];
            }
        }
        // A selection of itineraries we recommend
        // Get the itineraries with the ids 1, 2, 3, 4 and 5 from the database
        $getRecommendedItineraries = $itineraryRepository->findBy(['id' => [1, 2, 3, 4, 5]]);
        $recommendedItineraries = [];

        foreach ($getRecommendedItineraries as $itinerary) {
            // The duration of an itinerary is stored in minutes, so we will convert it to HH:MM format
            // before sending it to the view
            $itineraryTime = $itinerary->getTime();
            $time = '';

            if ($itineraryTime) {
                $hours = round($itineraryTime);
                $minutes = $itineraryTime - $hours / 100 * 60;
                $nbQuinzaine = round($minutes / 4);

                // Format the time as HH:MM
                $time = $hours.'h'.(15 * $nbQuinzaine);
            }

            $distance = $itinerary->getdistance().' km';

            // We will use a default image if the itinerary has no image
            $itineraryImage = $itinerary->getImage();
            $url = '/images/default.jpg';

            if (!empty($itineraryImage)) {
                // Get the BLOB field of the image
                $imageData = $itineraryImage->getImage();

                // If this is a stream, we have to read the content
                if (is_resource($imageData)) {
                    $imageData = stream_get_contents($imageData);
                }

                // Then we convert the string in base64 and save the source in the array that will be sent to the view
                $url = 'data:image/jpeg;base64,'.base64_encode($imageData);
            }

            $recommendedItineraries[] = [
                'id' => $itinerary->getId(),
                'name' => $itinerary->getName(),
                'description' => $itinerary->getDescription(),
                'time' => $time,
                'distance' => $distance,
                'note' => $itinerary->getNote(),
                'image' => $url,
            ];
        }
        $logon = false;
        if ($this->getUser()) {
            $logon = true;
        }

        return $this->render('itinerary/index.html.twig', [
            'user_itineraries' => $userItineraries,
            'recommended_itineraries' => $recommendedItineraries,
            'logon' => $logon,
        ]);
    }

    #[Route('/itinerary/{id}', name: 'app_itinerary_show', requirements: ['id' => '\d+'])]
    public function show(
        PathOrderRepository $pathOrderRepository,
        #[MapEntity(expr: 'repository.find(id)')] ?Itinerary $itinerary = null,
    ): Response {
        if (!$itinerary) {
            return $this->redirectToRoute('app_itinerary');
        }
        // The duration of an itinerary is stored in minutes, so we will convert it to HH:MM format
        // before sending it to the view
        $itineraryTime = $itinerary->getTime();
        $time = '';

        if ($itineraryTime) {
            $hours = round($itineraryTime);
            $minutes = $itineraryTime - $hours / 100 * 60;
            $nbQuinzaine = round($minutes / 4);

            // Format the time as HH:MM
            $time = $hours.'h'.(15 * $nbQuinzaine);
        }

        $distance = $itinerary->getdistance().' km';

        $itineraryOrder = $pathOrderRepository->findByItineraryId($itinerary->getId());
        $itineraryPlaces = [];

        foreach ($itineraryOrder as $element) {
            $itineraryPlaces[] = [
                'id' => $element->getPlace()->getId(),
                'name' => $element->getPlace()->getName(),
                'description' => $element->getPlace()->getDescription(),
                'address' => $element->getPlace()->getAddress(),
                'latitude' => $element->getPlace()->getLatitude(),
                'longitude' => $element->getPlace()->getLongitude(),
            ];
        }

        $own = false;
        if ($this->getUser() && $itinerary->getUser()->getId() != $this->getUser()->getId()) {
            $own = true;
        }

        return $this->render('itinerary/show.html.twig', [
            'itinerary' => $itinerary,
            'time' => $time,
            'distance' => $distance,
            'places' => $itineraryPlaces,
            'own' => $own,
        ]);
    }


    #[Route('/itinerary/create', name: 'app_create_itinerary')]
    public function create(
        EntityManagerInterface $entityManager,
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $itinerary = new Itinerary();
        $itinerary->setUser($this->getUser());
        $itinerary->setName('Nouveau itinéraire');
        $entityManager->persist($itinerary);
        $entityManager->flush();
        $this->addFlash('notice', 'create');

        return $this->redirectToRoute('app_update_itinerary', ['id' => $itinerary->getId()]);
    }

    #[Route('/itinerary/update/{id}', name: 'app_update_itinerary',  requirements: ['id' => '\d+'])]
    public function update(
        #[MapEntity(expr: 'repository.findWithPlace(id)')] ?Itinerary $itinerary,
        Request $request,
        EntityManagerInterface $entityManager,
        PlaceRepository $placeRepository,
        ItineraryRepository $itineraryRepository,
        #[MapQueryParameter(options: ['regexp' => '[a-zA-Z]+'])] string $search = '',
    ): Response {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!$itinerary) {
            return $this->redirectToRoute('app_itinerary');
        }
        if ($itinerary->getUser()->getId() != $this->getUser()->getId()) {
            return $this->redirectToRoute('app_itinerary');
        }
        $form = $this->createForm(ItineraryType::class, $itinerary);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            if ($itineraryRepository->countPlaceForOne($itinerary->getId()) < 2) {
                $this->addFlash('warning', 'Il faut au minimum 2 lieux dans un itinéraire');

                return $this->redirectToRoute('app_update_itinerary', ['id' => $itinerary->getId()]);
            }
            $itinerary = $form->getData();
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
                    $dlat = $lat2 - $lat1;
                    $dlng = $lng2 - $lng1;
                    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2)
                        * sin($dlng / 2) * sin($dlng / 2);
                    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                    $km = 6372.797 * $c; // rayon moyen de la Terre en km

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
            $entityManager->flush();
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_itinerary_show', ['id' => $itinerary->getId()]);
        }
        $places = $placeRepository->searchWithoutOneItinerary($search, $itinerary->getId());

        return $this->render('itinerary/update.html.twig', [
            'form' => $form,
            'itinerary' => $itinerary,
            'places' => $places,
            'Route' => 'update',
        ]);
    }

    #[Route('/itinerary/add', name: 'app_itinerary_add', methods: ['POST'])]
    public function addPlace(Request $request, PlaceRepository $placeRepository, ItineraryRepository $itineraryRepository, EntityManagerInterface $entityManager): Response
    {
        $pos = (int) $request->request->get('position');

        $itinerary = $itineraryRepository->find($request->request->get('itinerary_id'));
        $place = $placeRepository->find($request->request->get('place_id'));
        if (!$itinerary || !$place) {
            return $this->json(['message' => 'Itinéraire ou lieu non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        foreach ($itinerary->getPathOrder() as $path) {
            if ($path->getPlace()->getId() === $place->getId()) {
                return $this->json('Lieu déjà dans le parcours.', Response::HTTP_BAD_REQUEST, [], ['json_encode_options' => JSON_UNESCAPED_UNICODE]);
            }
            if ($path->getPosition() == $pos) {
                return $this->json('Position déjà utilisée.', Response::HTTP_BAD_REQUEST, [], ['json_encode_options' => JSON_UNESCAPED_UNICODE]);
            }
        }
        $pathOrder = new PathOrder();
        $pathOrder->setItinerary($itinerary);
        $pathOrder->setPlace($place);
        $pathOrder->setPosition($pos);

        $entityManager->persist($pathOrder);
        $entityManager->flush();

        return $this->json(['message' => 'Lieu ajouté à l\'itineraire!'], Response::HTTP_OK);
    }

    #[Route('/itinerary/remove', name: 'app_itinerary_remove', methods: ['POST'])]
    public function removePlace(Request $request, EntityManagerInterface $entityManager, PathOrderRepository $pathOrderRepository): Response
    {
        $pathOrder = $pathOrderRepository->find($request->request->get('path_order_id'));

        if ($pathOrder) {
            $entityManager->remove($pathOrder);
            $entityManager->flush();

            return $this->json(['message' => 'Lieu retiré de l\'itineraire.'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'Itinéraire ou lieu non trouvé.'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/itinerary/getPathOrder', name: 'app_itinerary_getPathOrder', methods: ['POST'])]
    public function getPathOrder(
        Request $request,
        ItineraryRepository $itineraryRepository,
        PlaceRepository $placeRepository,
        PathOrderRepository $pathOrderRepository,
    ): Response {
        $itinerary = $itineraryRepository->findOneBy(['id' => $request->request->get('itinerary_id')]);
        $place = $placeRepository->findOneBy(['id' => $request->request->get('place_id')]);

        $pathOrder = $pathOrderRepository->findOneBy(['itinerary' => $itinerary->getId(), 'place' => $place->getId()]);
        if ($pathOrder) {
            return $this->json($pathOrder->getId(), Response::HTTP_OK);
        }

        return $this->json(['message' => 'Itinéraire ou lieu non trouvé.'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/itinerary/delete', name: 'app_itinerary_delete', methods: ['POST'])]
    public function deleleteItinerary(Request $request, EntityManagerInterface $entityManager, ItineraryRepository $itineraryRepository): Response
    {
        $itinerary = $itineraryRepository->find($request->request->get('itinerary'));

        if ($itinerary) {
            $entityManager->remove($itinerary);
            $entityManager->flush();

            return $this->json(['message' => 'Lieu retiré de l\'itineraire.'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'Itinéraire ou lieu non trouvé.'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/itinerary/favoris/add', name: 'app_add_favoris_itinerary', methods: ['POST'])]
    public function addToFavoris(EntityManagerInterface $entityManager, Request $request): Response
    {
        $itinerary = $entityManager->getRepository(Itinerary::class)->find($request->request->get('id'));
        $user = $this->getUser();
        if ($user && $itinerary) {
            foreach ($user->getFavoriteItinerary() as $favoriteitinerary) {
                if ($favoriteitinerary->getId() === $itinerary->getId()) {
                    return $this->json(['message' => 'Lieu deja dans les favoris'], Response::HTTP_BAD_REQUEST);
                }
            }
            $user->addFavoriteItinerary($itinerary);
            $itinerary->addUsers($user);
            $entityManager->flush();

            return $this->json(['message' => 'Favoris mis a jour.'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'Lieu non trouve.'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/itinerary/favoris/remove', name: 'app_remove_favoris_itinerary', methods: ['POST'])]
    public function removeOfFavoris(EntityManagerInterface $entityManager, Request $request): Response
    {
        $itinerary = $entityManager->getRepository(Itinerary::class)->find($request->request->get('id'));
        $user = $this->getUser();
        if ($user && $itinerary) {
            $user->removeFavoriteItinerary($itinerary);
            $itinerary->removeUsers($user);
            $entityManager->flush();

            return $this->json(['message' => 'Lieu supprimer des favoris'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'Lieu non trouvé.'], Response::HTTP_NOT_FOUND);
    }


    #[Route('/itinerary/update/{slug}', name: 'app_itinerary_update_incorrect')]
    public function updateIncorrect(): RedirectResponse
    {
        return $this->redirectToRoute('app_itinerary');
    }

    #[Route('/itinerary/{slug}', name: 'app_itinerary_incorrect')]
    public function itineraryIdIncorrect(): RedirectResponse
    {
        return $this->redirectToRoute('app_itinerary');
    }
}
