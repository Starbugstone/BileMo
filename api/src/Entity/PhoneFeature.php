<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PhoneFeatureRepository")
 */
class PhoneFeature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_phone"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Groups({"get_phone"})
     */
    private $unit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneHasFeature", mappedBy="phoneFeature", orphanRemoval=true)
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

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

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
            $phoneHasFeature->setPhoneFeature($this);
        }

        return $this;
    }

    public function removePhoneHasFeature(PhoneHasFeature $phoneHasFeature): self
    {
        if ($this->phoneHasFeatures->contains($phoneHasFeature)) {
            $this->phoneHasFeatures->removeElement($phoneHasFeature);
            // set the owning side to null (unless already changed)
            if ($phoneHasFeature->getPhoneFeature() === $this) {
                $phoneHasFeature->setPhoneFeature(null);
            }
        }

        return $this;
    }
}
