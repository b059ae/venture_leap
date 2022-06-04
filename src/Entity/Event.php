<?php

namespace App\Entity;

use App\Repository\EventRepository;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'events'/*, fetch: 'EAGER'*/)]
    #[Assert\NotBlank]
    private ?Type $type;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private string $details;

    #[ORM\Column(name: "created_at", type: 'integer')]
    #[Assert\NotBlank]
    private $createdAt;

    public function __construct()
    {
        $this->createdAt = (new \DateTime())->getTimestamp();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return (new \DateTime())->setTimestamp($this->createdAt);
    }

    public function setCreatedAt(?int $createdAt): self
    {
        if ($createdAt !== null){
            $this->createdAt = $createdAt;
        }
        return $this;
    }
}
