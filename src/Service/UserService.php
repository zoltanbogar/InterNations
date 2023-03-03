<?php

namespace App\Service;

use App\Entity\Membership;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserService
{
    private $managerRegistry;
    private $validator;

    public function __construct(ManagerRegistry $managerRegistry, ValidatorInterface $validator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->validator = $validator;
    }

    public function createUser($params): array
    {
        if ($params['_email'] === '' ||
            $params['_password'] === '' ||
            $params['_name'] === '') {
            return [
                'error' => 'All inputs has to have a value!',
                'message' => null,
            ];
        }

        $user = new User();
        $user->setEmail($params['_email']);
        $user->setPassword($params['_password']);
        $user->setName($params['_name']);
        $user->setRoles(['ROLE_USER']);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            return [
                'error' => (string)$errors,
                'message' => null,
            ];
        }

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return [
            'error' => null,
            'message' => "User with the email: {$params['_email']} was successfully created.",
        ];
    }

    public function deleteUser($id): array
    {
        $user = $this->managerRegistry->getRepository(User::class)->find($id);
        if (!$user) {
            return [
                'error' => 'User does not exist!',
                'message' => null,
            ];
        }

        $em = $this->managerRegistry->getManager();

        try {
            foreach ($user->getMemberships() as $group) {
                $user->removeMembership($group);
            }
            $em->flush();

            $em->remove($user);
            $em->flush();

            return [
                'error' => null,
                'message' => 'User deleted!',
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'message' => null,
            ];
        }
    }

    public function addUserToGroup($usedId, $groupId): array
    {
        $user = $this->managerRegistry->getRepository(User::class)->find($usedId);
        if (!$user) {
            return [
                'error' => 'User does not exist!',
                'message' => null,
            ];
        }

        $group = $this->managerRegistry->getRepository(Membership::class)->find($groupId);
        if (!$group) {
            return [
                'error' => 'Group does not exist!',
                'message' => null,
            ];
        }

        try {
            $user->addMembership($group);
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return [
                'error' => null,
                'message' => 'User added to group!',
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'message' => null,
            ];
        }
    }

    public function removeUserFromGroup($usedId, $groupId): array
    {
        $user = $this->managerRegistry->getRepository(User::class)->find($usedId);
        if (!$user) {
            return [
                'error' => 'User does not exist!',
                'message' => null,
            ];
        }
        $group = $this->managerRegistry->getRepository(Membership::class)->find($groupId);
        if (!$group) {
            return [
                'error' => 'Group does not exist!',
                'message' => null,
            ];
        }
        try {
            $user->removeMembership($group);
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return [
                'error' => null,
                'message' => 'User removed from group!',
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'message' => null,
            ];
        }
    }
}