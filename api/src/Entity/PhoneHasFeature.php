<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\PhoneHasFeatureRepository")
 */
class PhoneHasFeature
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phone", inversedBy="phoneHasFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PhoneFeature", inversedBy="phoneHasFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phoneFeature;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function setPhone(?Phone $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhoneFeature(): ?PhoneFeature
    {
        return $this->phoneFeature;
    }

    public function setPhoneFeature(?PhoneFeature $phoneFeature): self
    {
        $this->phoneFeature = $phoneFeature;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
