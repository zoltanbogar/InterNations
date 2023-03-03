<?php

namespace App\Controller;

use App\Service\GroupService;
use App\Service\MembershipService;
use App\Service\ResponseService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MembershipController extends AbstractController
{
    #[Route('/groups', name: 'home_group')]
    public function createGroup(Request $request, MembershipService $membershipService, ResponseService $responseService, ManagerRegistry $doctrine)
    {
        $response = [];

        if ($request->isMethod('POST')) {
            ['error' => $error, 'message' => $message] = $membershipService->createGroup($request->request->all());
            $response = [
                'error' => $error,
                'message' => $message,
            ];
        }

        ['templatePath' => $templatePath, 'returnResponse' => $returnResponse] = $responseService->getRenderHomeParams($response);

        return $this->render($templatePath, $returnResponse);
    }

    #[Route('/groups/{id}/delete', name: 'group_delete')]
    public function userDelete(int $id, MembershipService $membershipService, ResponseService $responseService)
    {
        ['error' => $error, 'message' => $message] = $membershipService->deleteGroup($id);
        $response = [
            'error' => $error,
            'message' => $message,
        ];

        ['templatePath' => $templatePath, 'returnResponse' => $returnResponse] = $responseService->getRenderHomeParams($response);

        return $this->render($templatePath, $returnResponse);
    }
}