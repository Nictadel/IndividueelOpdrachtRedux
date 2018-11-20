<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Treinetic\ImageArtist\lib\Image;

class ImageController extends AbstractController
{
    /**
     * @Route("/images", methods={"GET"}, name="getImage")
     */
    public function getAllImage()
    {
        $statuscode = 200;
        $imageCity = new Image(__DIR__.'/../Images/city.jpg');
        $imageCity->scale(5);
        $imageForest = new Image(__DIR__.'/../Images/forest.jpg');
        $imageForest->scale(5);


        $base64Forest = $imageForest->getDataURI(IMAGETYPE_JPEG);
        $base64City = $imageCity->getDataURI(IMAGETYPE_JPEG);

        return new JsonResponse(array('imageForest' => $base64Forest, 'imageCity' => $base64City), $statuscode);
    }
}
