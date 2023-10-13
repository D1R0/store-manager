<?php

namespace App\Entity;

use App\Repository\StoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Doctrine\ORM\Mapping\CustomIdGenerator;


#[ORM\Entity(repositoryClass: StoreRepository::class)]
class Store
{

    const WooCommerce_TYPE = "WooCommerce";
    const WooCommerce_Details = [
        "domain" => "", "customer_key" => "", "customer_secret" => ""
    ];

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true, nullable: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: "doctrine.uuid_generator")]
    private  $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'store')]
    public $owner;

    #[ORM\Column]
    private ?string $name = null;

    #[ORM\Column]
    private ?string $type = null;


    #[ORM\Column]
    private array $details = [];


    #[ORM\Column(nullable: true)]
    private $icon = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * Set the value of creator
     *
     * @return  self
     */
    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get the value of type
     *
     * @return ?string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param ?string $type
     *
     * @return self
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of details
     *
     * @return array
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    /**
     * Set the value of details
     *
     * @param array $details
     *
     * @return self
     */
    public function setDetails(array $details): self
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get the value of icon
     *
     * @return ?string
     */
    public function getIcon(): ?string
    {
        return $this->icon;
    }

    /**
     * Set the value of icon
     *
     * @param ?string $icon
     *
     * @return self
     */
    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return ?string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param ?string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
