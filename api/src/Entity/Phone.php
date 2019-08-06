<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneHasFeature", mappedBy="phone", orphanRemoval=true)
     */
    private $phoneHasFeatures;

    public function __construct()
    {
        $this->phoneHasFeatures = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection|PhoneHasFeature[]
     */
    public function getPhoneHasFeatures(): Collection
    {
        return $this->phoneHasFeatures;
    }

    public function addPhoneHasFeature(PhoneHasFeature $phoneHasFeature): self
    {
        if (!$this->phoneHasFeatures->contains($phoneHasFeature)) {
            $this->phoneHasFeatures[] = $phoneHasFeature;
            $phoneHasFeature->setPhone($this);
        }

        return $this;
    }

    public function removePhoneHasFeature(PhoneHasFeature $phoneHasFeature): self
    {
        if ($this->phoneHasFeatures->contains($phoneHasFeature)) {
            $this->phoneHasFeatures->removeElement($phoneHasFeature);
            // set the owning side to null (unless already changed)
            if ($phoneHasFeature->getPhone() === $this) {
                $phoneHasFeature->setPhone(null);
            }
        }

        return $this;
    }
}
