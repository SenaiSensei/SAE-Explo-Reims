<?php

namespace App\Controller;

use App\Entity\Place;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

class PlaceController extends AbstractController
{
    #[Route('/place', name: 'app_place')]
    public function index(
        PlaceRepository $p,
        #[MapQueryParameter(options: ['regexp' => '[a-zA-Z]+'])] string $search = '',
    ): Response {
        $user = $this->getUser();
        $isgood = null;
        $place = null;
        $favoritePlace = null;
        if ($user) {
            $isgood = [];
            $places = $p->searchPlaceWithCatg($search, $user->getId());
            $favoritePlace = $user->getFavoritePlace();
            foreach ($places as $place) {
                $isgood[$place->getId()] = false;
            }
            foreach ($favoritePlace as $place) {
                $isgood[$place->getId()] = true;
            }
        } else {
            $places = $p->searchPlaceWithCatg($search);
        }

        return $this->render('place/index.html.twig', [
            'places' => $places,
            'isGood' => $isgood,
            'favoritePlace' => $favoritePlace,
        ]);
    }

    #[Route('/place/{id}', name: 'app_placeById', requirements: ['id' => '\d+'])]
    public function placeById(#[MapEntity(expr: 'repository.findAllForOne(id)')] ?Place $place = null): Response
    {
        if (!$place) {
            return $this->redirectToRoute('app_place');
        }

        return $this->render('place/placeById.html.twig', [
            'place' => $place,
        ]);
    }

    #[Route('/place/{slug}', name: 'app_place_incorrect')]
    public function placeIdIncorrect(): RedirectResponse
    {
        return $this->redirectToRoute('app_place');
    }

    #[Route('/place/favoris/add', name: 'app_add_favoris_place', methods: ['POST'])]
    public function addToFavoris(EntityManagerInterface $entityManager, Request $request): Response
    {
        $place = $entityManager->getRepository(Place::class)->find($request->request->get('id'));
        $user = $this->getUser();
        if ($user && $place) {
            foreach ($user->getFavoritePlace() as $favoritePlace) {
                if ($favoritePlace->getId() === $place->getId()) {
                    return $this->json(['message' => 'Lieu deja dans les favoris'], Response::HTTP_BAD_REQUEST);
                }
            }
            $user->addFavoritePlace($place);
            $place->addUser($user);
            $entityManager->flush();

            return $this->json(['message' => 'Favoris mis a jour.'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'Lieu non trouve.'], Response::HTTP_NOT_FOUND);
    }

    #[Route('/place/favoris/remove', name: 'app_remove_favoris_place', methods: ['POST'])]
    public function removeOfFavoris(EntityManagerInterface $entityManager, Request $request): Response
    {
        $place = $entityManager->getRepository(Place::class)->find($request->request->get('id'));
        $user = $this->getUser();
        if ($user && $place) {
            $user->removeFavoritePlace($place);
            $place->removeUser($user);
            $entityManager->flush();

            return $this->json(['message' => 'Lieu supprimer des favoris'], Response::HTTP_OK);
        }

        return $this->json(['message' => 'Lieu non trouvé.'], Response::HTTP_NOT_FOUND);
    }
}
