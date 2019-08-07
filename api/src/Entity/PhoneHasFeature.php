<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ ApiResource()
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
     * @var Phone $phone the associated telephone
     * @ORM\ManyToOne(targetEntity="App\Entity\Phone", inversedBy="phoneHasFeatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phone;

    /**
     * @var PhoneFeature $phoneFeature The associated feature
     * @ORM\ManyToOne(targetEntity="App\Entity\PhoneFeature", inversedBy="phoneHasFeatures")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get_phone"})
     */
    private $phoneFeature;

    /**
     * @var string $value the value of the feature
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_phone"})
     *
     * @Assert\NotBlank
     *
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
