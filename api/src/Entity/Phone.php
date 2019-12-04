<?php
// // api\src\Entity\phone.php

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
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ApiResource(
 *     attributes={"order"={"releaseDate": "ASC"}},
 *
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"get_phones"}}
 *          },
 *          "post"={
 *              "denormalization_context"={"groups"={"post_phone"}},
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     itemOperations={
 *         "get"={
 *             "normalization_context"={"groups"={"get_phone"}}
 *         },
 *         "put"={
 *             "denormalization_context"={"groups"={"put_phone"}},
 *              "security"="is_granted('ROLE_ADMIN')"
 *         },
 *         "delete"={"security"="is_granted('ROLE_ADMIN')"},
 *     }
 *
 * )
 *
 * @ApiFilter(
 *     SearchFilter::class,
 *     properties={
 *          "name": "ipartial",
 *          "description": "ipartial",
 *     }
 * )
 *
 * @ApiFilter(
 *     RangeFilter::class,
 *     properties={
 *          "price",
 *          "releaseDate"
 *     }
 *
 * )
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
     * @var string $name The name of the Phone
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     *
     * @Assert\NotBlank
     *
     */
    private $name;

    /**
     * @var float $price The price of the Phone
     * @ORM\Column(type="float")
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     *
     * @Assert\NotBlank
     * @Assert\Range(min=0, minMessage="The price must be superior to 0.")
     * @Assert\Type(type="float")
     *
     */
    private $price;

    /**
     * @var string $description The long description of the Phone
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     */
    private $description;

    /**
     * @var \DateTimeInterface $releaseDate The date the phone came out
     * @ORM\Column(type="datetime")
     * @Groups({"get_phone", "post_phone", "get_phones", "put_phone"})
     *
     * @Assert\Date
     * @Assert\GreaterThan("1973-04-03", message="the input of {{ value }} is before the creation of the first mobile phone, respect Martin Cooper and all the hard work he did on {{ compared_value }}") #the first mobile phone was invented by martin cooper
     *
     */
    private $releaseDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneHasFeature", mappedBy="phone", orphanRemoval=true)
     * @Groups({"get_phone"})
     *
     */
    private $phoneHasFeatures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneImage", mappedBy="phone")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get_phone", "get_phones"})
     */
    private $phoneImages;

    public function __construct()
    {
        $this->phoneHasFeatures = new ArrayCollection();
        $this->phoneImages = new ArrayCollection();
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

    /**
     * @return Collection|PhoneImage[]
     */
    public function getPhoneImages(): Collection
    {
        return $this->phoneImages;
    }

    public function addPhoneImage(PhoneImage $phoneImage): self
    {
        if (!$this->phoneImages->contains($phoneImage)) {
            $this->phoneImages[] = $phoneImage;
            $phoneImage->setPhone($this);
        }

        return $this;
    }

    public function removePhoneImage(PhoneImage $phoneImage): self
    {
        if ($this->phoneImages->contains($phoneImage)) {
            $this->phoneImages->removeElement($phoneImage);
            // set the owning side to null (unless already changed)
            if ($phoneImage->getPhone() === $this) {
                $phoneImage->setPhone(null);
            }
        }

        return $this;
    }
}
