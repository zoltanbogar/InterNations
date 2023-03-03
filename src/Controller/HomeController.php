<?php

namespace App\Controller;

use App\Service\ResponseService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ResponseService $responseService)
    {
        ['templatePath' => $templatePath, 'returnResponse' => $returnResponse] = $responseService->getRenderHomeParams([]);

        return $this->render($templatePath, $returnResponse);
    }
}