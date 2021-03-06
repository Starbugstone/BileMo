<?php
// api\src\Entity\PhoneImage.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\CreatePhoneImageAction;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="/phones/images",
 *          },
 *          "post"={
 *              "path"="/phones/images",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "controller"=CreatePhoneImageAction::class,
 *              "deserialize"=false,
 *              "validation_groups"={"Default", "phone_image_create"},
 *              "openapi_context"={
 *                "requestBody"={
 *                  "content"={
 *                    "multipart/form-data"={
 *                       "schema"={
 *                         "type"="object",
 *                         "properties"={
 *                            "imageFile"={
 *                               "type"="string",
 *                               "format"="binary"
 *                            },
 *                            "phone"={
 *                               "type"="int",
 *                               "description"="ID of the attached phone",
 *                            },
 *                            "title"={
 *                               "type"="string",
 *                               "description"="title of the image",
 *                            }
 *
 *                         }
 *                       }
 *                     }
 *                  }
 *                }
 *             },
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="/phones/images/{id}",
 *              "requirements"={"id"="\d+"},
 *          },
 *          "put"={
 *              "path"="/phones/images/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "denormalization_context"={"groups"={"put_phone_image"}},
 *          },
 *          "delete"={
 *              "path"="/phones/images/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('ROLE_ADMIN')",
 *          },
 *     },
 * )
 * @ORM\Entity(repositoryClass="App\Repository\PhoneImageRepository")
 * @Vich\Uploadable()
 */
class PhoneImage
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_phone", "read"})
     */
    private $image;


    /**
     * @Vich\UploadableField(mapping="phone_images", fileNameProperty="image")
     * @Assert\NotNull(groups={"phone_image_create"})
     * @Groups({"put_phone_image"})
     * @var File
     */
    private $imageFile;

    /**
     * @var string $imageUrl Generated URL for the image
     * @Groups({"get_phone", "get_phones", "read"})
     */
    public $imageUrl;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Phone", inversedBy="phoneImages")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"read","put_phone_image"})
     */
    private $phone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @var string $title Title of the image
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get_phone", "get_phones", "read", "phone_image_create", "put_phone_image"})
     */
    private $title;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt = null): self
    {
        //Helper if we omit to set the updated time, we automaticly set it here
        if ($updatedAt === null) {
            $updatedAt = new \DateTime();
        }
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($imageFile) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
//        return null;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }


    public function setImageUrl(string $imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
