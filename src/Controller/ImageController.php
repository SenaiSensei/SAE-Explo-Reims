<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Image;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    #[Route('/image')]
    public function index(): Response
    {
        return $this->render('image/index.html.twig');
    }

    #[Route('/image/{id}', name: 'app_imageById', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function imageById(?Image $image): Response
    {
        if (null == $image) {
            $rendu = readfile('https://127.0.0.1:8000/images/default.jpg', true);
        } else {
            $rendu = stream_get_contents($image->getImage(), -1, 0);
        }

        return new Response(
            $rendu,
            200,
            ['Content-Type' => 'image/png']
        );
    }

    #[Route('/image/place/{id}', name: 'app_imageByPlaceId', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function imageByPlaceId(#[MapEntity(expr: 'repository.getOneImageByPlaceId(id)')] ?Image $image): Response
    {
        dump($image);
        if (null == $image) {
            $rendu = file_get_contents($this->getParameter('kernel.project_dir').'/public/images/default.jpg'); // read the default image
        } else {
            $rendu = stream_get_contents($image->getImage(), -1, 0); // read the DB image
        }

        return new Response(
            $rendu,
            200,
            ['Content-Type' => 'image/png']
        );
    }
}
