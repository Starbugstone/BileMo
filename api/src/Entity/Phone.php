<?php

namespace App\Entity;

#The filters
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     attributes={"order"={"releaseDate": "ASC"}},
 *     normalizationContext={"groups"={"get_phones"}},
 *     denormalizationContext={"groups"={"post_phone"}},
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"get_phone"}}
 *         },
 *         "put"={
 *             "normalization_context"={"groups"={"put_phone"}}
 *         }
 *     }
 *
 * )
 *
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *      "id": "exact",
 *      "name": "partial",
 *      "description": "partial",
 *      "phoneHasFeatures.value": "exact",
 *      "phoneHasFeatures.phoneFeature.name": "exact",
 *      "phoneHasFeatures.value": "exact",
 *     }
 * )
 *
 * @ApiFilter(RangeFilter::class, properties={"price"})
 *
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
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     */
    private $releaseDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneHasFeature", mappedBy="phone", orphanRemoval=true)
     * @Groups({"get_phone"})
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
