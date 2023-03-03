<?php

namespace App\Entity;

use App\Repository\MembershipRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: MembershipRepository::class)]
#[ORM\Table(name: '`membership`')]
#[UniqueEntity(fields: ['name'], message: 'There is already a group with this name')]
class Membership
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 180, unique: true)]
    private ?string $name = null;
    /**
     * Many Groups have Many Users.
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'memberships')]
    private Collection $members;

    public function __construct() {
        $this->members = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMember(): ArrayCollection|Collection
    {
        return $this->members;
    }

    public function addMember(User $member): self
    {
        $this->members[$member->getId()] = $member;

        return $this;
    }

    public function removeMember(User $member): self
    {
        unset($this->members[$member->getId()]);

        return $this;
    }
}