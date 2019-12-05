<?php
// api\src\Entity\PhoneFeature.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read_feature"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/phones/features/type"
 *          },
 *          "post"={
 *              "path"="/phones/features/type",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "denormalization_context"={"groups"={"post_phone_feature"}},
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *              "path"="/phones/features/type/{id}",
 *              "requirements"={"id"="\d+"},
 *             "normalization_context"={"groups"={"get_feature"}}
 *          },
 *         "put"={
 *              "path"="/phones/features/type/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "denormalization_context"={"groups"={"post_phone_feature"}},
 *          },
 *         "delete"={
 *              "path"="/phones/features/type/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('ROLE_ADMIN')"
 *         },
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PhoneFeatureRepository")
 * @UniqueEntity("name")
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
     * @var string $name The name of the Feature
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"get_phone", "read_feature", "get_feature", "post_phone_feature"})
     *
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string|null $unit The unit of the feature (Kg, Gb, ...)
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Groups({"get_phone", "read_feature", "get_feature", "post_phone_feature"})
     */
    private $unit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneHasFeature", mappedBy="phoneFeature", orphanRemoval=true)
     * @Groups({"get_feature"})
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
