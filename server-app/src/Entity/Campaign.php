<?php

namespace App\Entity;

use App\Repository\CampaignRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Campaign
 * @package App\Entity
 * @user Jorge García <jgg.jobs.development@gmail.com>
 */
#[ORM\Entity(repositoryClass: CampaignRepository::class)]
class Campaign
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $discount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $fromAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $toAt = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float $discount
     * @return $this
     */
    public function setDiscount(float $discount): static
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getFromAt(): ?\DateTimeImmutable
    {
        return $this->fromAt;
    }

    /**
     * @param \DateTimeImmutable $fromAt
     * @return $this
     */
    public function setFromAt(\DateTimeImmutable $fromAt): static
    {
        $this->fromAt = $fromAt;

        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getToAt(): ?\DateTimeImmutable
    {
        return $this->toAt;
    }

    /**
     * @param \DateTimeImmutable|null $toAt
     * @return $this
     */
    public function setToAt(?\DateTimeImmutable $toAt): static
    {
        $this->toAt = $toAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     * @return $this
     */
    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }
}
