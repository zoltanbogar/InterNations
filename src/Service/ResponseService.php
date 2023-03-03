<?php

namespace App\Service;

use App\Entity\Membership;
use App\Entity\User;
use App\Entity\UserGroup;
use Doctrine\Persistence\ManagerRegistry;

class ResponseService
{
    const HOME_INDEX = 'home/index.html.twig';
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getRenderHomeParams($response): array
    {
        $users = $this->managerRegistry->getRepository(User::class)->findAll();
        $response['users'] = $users;

        $memberships = $this->managerRegistry->getRepository(Membership::class)->findAll();
        $response['memberships'] = $memberships;

        return [
            'templatePath' => self::HOME_INDEX,
            'returnResponse' => $response,
        ];
    }
}