<?php

namespace App\Entity;

use App\Enum\BidState;
use App\Repository\BidRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BidRepository::class)]
class Bid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Slot $slot = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $buyer = null;

    #[ORM\Column(length: 50)]
    private ?string $state = null;

    #[ORM\Column]
    private ?float $value = null;

    public static function create(
        User $buyer,
        Slot $slot,
        float $value
    ): self
    {
        $bid = new Bid();
        $bid->setBuyer($buyer);
        $bid->setState(BidState::PROCESSING->value);
        $bid->setSlot($slot);
        $bid->setValue($value);

        return $bid;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlot(): Slot
    {
        return $this->slot;
    }

    public function setSlot(?Slot $slot): static
    {
        $this->slot = $slot;

        return $this;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): static
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function markAsSuccessful(): void
    {
        $this->setState(BidState::SUCCESS->value);
    }

    public function markAsFailed(): void
    {
        $this->setState(BidState::FAILED->value);
    }
}
