<?php

namespace App\Service;

use App\Entity\Membership;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MembershipService
{
    private $managerRegistry;
    private $validator;

    public function __construct(ManagerRegistry $managerRegistry, ValidatorInterface $validator)
    {
        $this->managerRegistry = $managerRegistry;
        $this->validator = $validator;
    }

    public function createGroup($params): array
    {
        if ($params['_name'] === '') {
            return [
                'error' => 'Name is mandatory!',
                'message' => null,
            ];
        }

        $group = new Membership();
        $group->setName($params['_name']);

        $errors = $this->validator->validate($group);

        if (count($errors) > 0) {
            return [
                'error' => (string)$errors,
                'message' => null,
            ];
        }

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($group);
        $entityManager->flush();

        return [
            'error' => null,
            'message' => "Group with the name: {$params['_name']} was successfully created.",
        ];
    }

    public function deleteGroup($id): array
    {
        $group = $this->managerRegistry->getRepository(Membership::class)->find($id);
        if (!$group) {
            return [
                'error' => 'Group does not exist!',
                'message' => null,
            ];
        }

        $em = $this->managerRegistry->getManager();

        try {
            if (count($group->getMember()) === 0) {
                $em->remove($group);
                $em->flush();

                return [
                    'error' => null,
                    'message' => 'Group deleted!',
                ];
            }

            return [
                'error' => 'Group is not empty!',
                'message' => null,
            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'message' => null,
            ];
        }
    }
}