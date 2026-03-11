<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Class DefaultController
 * @package App\Controller
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
final class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): JsonResponse
    {
        throw $this->createNotFoundException();
    }
}
