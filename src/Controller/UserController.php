<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserGroup;
use App\Service\ResponseService;
use App\Service\UserService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/users', name: 'home_user')]
    public function createUser(Request $request, UserService $userService, ResponseService $responseService, ManagerRegistry $doctrine)
    {
        $response = [];

        if ($request->isMethod('POST')) {
            ['error' => $error, 'message' => $message] = $userService->createUser($request->request->all());
            $response = [
                'error' => $error,
                'message' => $message,
            ];
        }

        ['templatePath' => $templatePath, 'returnResponse' => $returnResponse] = $responseService->getRenderHomeParams($response);

        return $this->render($templatePath, $returnResponse);
    }

    #[Route('/users/{id}/delete', name: 'user_delete')]
    public function userDelete(int $id, UserService $userService, ResponseService $responseService)
    {
        ['error' => $error, 'message' => $message] = $userService->deleteUser($id);
        $response = [
            'error' => $error,
            'message' => $message,
        ];

        ['templatePath' => $templatePath, 'returnResponse' => $returnResponse] = $responseService->getRenderHomeParams($response);

        return $this->render($templatePath, $returnResponse);
    }

    #[Route('/users/{id}/add-to-group', name: 'user_to_group')]
    public function userAddToGroup(int $id, Request $request, UserService $userService, ResponseService $responseService)
    {
        ['error' => $error, 'message' => $message] = $userService->addUserToGroup($id, $request->request->get('group_id'));
        $response = [
            'error' => $error,
            'message' => $message,
        ];

        ['templatePath' => $templatePath, 'returnResponse' => $returnResponse] = $responseService->getRenderHomeParams($response);

        return $this->render($templatePath, $returnResponse);
    }

    #[Route('/users/{id}/remove-from-group', name: 'user_remove_from_group')]
    public function userRemoveFromGroup(int $id, Request $request, UserService $userService, ResponseService $responseService)
    {
        ['error' => $error, 'message' => $message] = $userService->removeUserFromGroup($id, $request->request->get('group_id'));
        $response = [
            'error' => $error,
            'message' => $message,
        ];

        ['templatePath' => $templatePath, 'returnResponse' => $returnResponse] = $responseService->getRenderHomeParams($response);

        return $this->render($templatePath, $returnResponse);
    }
}