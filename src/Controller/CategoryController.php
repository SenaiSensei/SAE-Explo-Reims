<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository, UserRepository $userRepository): Response
    {
        $categories = $categoryRepository->findAllWithPlace();

        $user = $this->getUser();
        $isgood = null;
        if ($user) {
            $isgood = [];
            foreach ($categories as $category) {
                foreach ($category->getPlaces() as $place) {
                    $isgood[$place->getId()] = false;
                }
            }
            foreach ($user->getFavoritePlace() as $place) {
                $isgood[$place->getId()] = true;
            }
        }

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'isGood' => $isgood,
        ]);
    }

    #[Route('/category/{slug}', name: 'app_category_incorrect')]
    public function categoryIncorrect(): RedirectResponse
    {
        return $this->redirectToRoute('app_category');
    }
}
