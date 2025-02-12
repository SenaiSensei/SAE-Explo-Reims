<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Itinerary;
use App\Entity\Place;
use App\Entity\User;
use App\Form\CategoryType;
use App\Form\ItineraryType;
use App\Form\PlaceType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\ItineraryRepository;
use App\Repository\PlaceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_admin_analytics');
    }

    #[Route('/admin/analytics', name: 'app_admin_analytics')]
    public function showAnalytics(UserRepository $userRepository, PlaceRepository $placeRepository, ItineraryRepository $itineraryRepository): Response
    {
        $totalUsers = $userRepository->getTotalUsers();
        $bestPlace = $placeRepository->getPlaceWithHighestAverageNote();
        $lastUsers = $userRepository->findLatestLoggedInUsers();
        $bestItinerary = $itineraryRepository->findItineraryWithHighestNote();

        return $this->render('admin/analytics.html.twig', [
            'totalUsers' => $totalUsers,
            'bestPlace' => $bestPlace,
            'lastUsers' => $lastUsers,
            'bestItinerary' => $bestItinerary,
        ]);
    }

    #[Route('/admin/places', name: 'app_admin_places')]
    public function showPlaces(PlaceRepository $placeRepository): Response
    {
        $places = $placeRepository->findAll();

        return $this->render('admin/places.html.twig', [
            'places' => $places,
        ]);
    }

    #[Route('/admin/places/create', name: 'app_admin_create_places')]
    public function createPlaces(Request $request, EntityManagerInterface $entityManager): Response
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($place);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_places');
        }

        return $this->render('admin/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/places/delete', name: 'app_admin_delete_selected_places', methods: ['POST'])]
    public function deletePlaces(Request $request, PlaceRepository $placeRepository, EntityManagerInterface $entityManager): Response
    {
        $ids = $request->request->all('ids');

        if (!empty($ids)) {
            $places = $placeRepository->findBy(['id' => $ids]);

            foreach ($places as $place) {
                $entityManager->remove($place);
            }

            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_places');
    }

    #[Route('/admin/places/{id}/update', name: 'app_admin_update_places', requirements: ['id' => '\d+'])]
    public function updatePlaces(Place $place, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($place);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_places');
        }

        return $this->render('admin/update.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/places/{id}/delete', name: 'app_admin_delete_places', requirements: ['id' => '\d+'])]
    public function deletePlace(Place $place, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => ['class' => 'z-50 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700'],
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'z-50 bg-gray-500 text-black dark:text-white dark:bg-[#313438] py-2 px-4 rounded hover:bg-gray-500'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($place);
                $entityManager->flush();

                $this->addFlash('success', 'Le lieu a été supprimé avec succès.');

                return $this->redirectToRoute('app_admin_places');
            }

            return $this->redirectToRoute('app_admin_places');
        }

        return $this->render('admin/delete_places.html.twig', [
            'place' => $place,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/categories', name: 'app_admin_categories')]
    public function showCategories(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/admin/categories/create', name: 'app_admin_create_categories')]
    public function createCategories(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/create_category.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/categories/{id}/update', name: 'app_admin_update_categories', requirements: ['id' => '\d+'])]
    public function updateCategories(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/update_category.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/categories/{id}/delete', name: 'app_admin_delete_categories', requirements: ['id' => '\d+'])]
    public function deleteCategory(Category $category, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => ['class' => 'z-50 bg-red-600 text-white border py-2 px-4 rounded hover:bg-red-700'],
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'z-50 bg-gray-400 text-black dark:text-white dark:bg-[#313438] py-2 px-4 rounded hover:bg-gray-500'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($category);
                $entityManager->flush();

                $this->addFlash('success', 'Le lieu a été supprimé avec succès.');

                return $this->redirectToRoute('app_admin_categories');
            }

            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/delete_categories.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function showUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/create', name: 'app_admin_create_users')]
    public function createUsers(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            if ($plainPassword) {
                $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/create_user.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/users/{id}/update', name: 'app_admin_update_users', requirements: ['id' => '\d+'])]
    public function updateUsers(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'is_creation' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();

            if (empty($password)) {
                $user->setPassword($user->getPassword());
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/update_user.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'app_admin_delete_users', requirements: ['id' => '\d+'])]
    public function deleteUser(User $user, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => ['class' => 'bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700'],
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'z-50 bg-gray-500 text-black dark:text-white dark:bg-[#313438] py-2 px-4 rounded hover:bg-gray-500'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($user);
                $entityManager->flush();

                $this->addFlash('success', 'Le lieu a été supprimé avec succès.');

                return $this->redirectToRoute('app_admin_users');
            }

            return $this->redirectToRoute('app_admin_users');
        }

        return $this->render('admin/delete_users.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/itineraries', name: 'app_admin_itineraries')]
    public function showItineraries(ItineraryRepository $itineraryRepository): Response
    {
        $itineraries = $itineraryRepository->findAll();

        return $this->render('admin/itinerary/itineraries.html.twig', [
            'itineraries' => $itineraries,
        ]);
    }

    #[Route('/admin/itineraries/create', name: 'app_admin_create_itinerary')]
    public function createItinerary(Request $request, EntityManagerInterface $entityManager): Response
    {
        $itinerary = new Itinerary();

        $itinerary->setUser($this->getUser());

        $form = $this->createForm(ItineraryType::class, $itinerary);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($itinerary);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_itineraries');
        }

        return $this->render('admin/itinerary/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/admin/itineraries/{id}/update', name: 'app_admin_update_itinerary', requirements: ['id' => '\d+'])]
    public function updateItinerary(Itinerary $itinerary, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ItineraryType::class, $itinerary);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($itinerary);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_itineraries');
        }

        return $this->render('admin/itinerary/update.html.twig', [
            'itinerary' => $itinerary,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/itineraries/{id}/delete', name: 'app_admin_delete_itinerary', requirements: ['id' => '\d+'])]
    public function deleteItinerary(Itinerary $itinerary, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('delete', SubmitType::class, [
                'label' => 'Supprimer',
                'attr' => ['class' => 'bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700'],
            ])
            ->add('cancel', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'bg-gray-500 text-gray-500 dark:text-white py-2 px-4 rounded hover:bg-gray-600'],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($itinerary);
                $entityManager->flush();

                $this->addFlash('success', 'L\'itinéraire a été supprimé avec succès.');

                return $this->redirectToRoute('app_admin_itineraries');
            }

            return $this->redirectToRoute('app_admin_itineraries');
        }

        return $this->render('admin/itinerary/delete.html.twig', [
            'itinerary' => $itinerary,
            'form' => $form->createView(),
        ]);
    }


}
