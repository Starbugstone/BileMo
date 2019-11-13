<?php
// api/src/Entity/ClientUser.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\CreateClientUserAction;

/**
 * the get is limited by the doctrine extension to only retreive our own clientUsers
 * @ApiResource(
 *     normalizationContext={"groups"={"client_read"}},
 *     denormalizationContext={"groups"={"client_write"}},
 *     collectionOperations={
 *          "get",
 *          "post_newClientUser"={
 *              "method"="POST",
 *              "path"="/client_users",
 *              "controller"=CreateClientUserAction::class,
 *          }
 *      },
 *     itemOperations={
 *          "put" = {
 *              "access_control" = "is_granted('SELF_AND_ADMIN', previous_object)",
 *          },
 *          "get" = {
 *              "access_control" = "is_granted('SELF_AND_ADMIN', previous_object)",
 *          },
 *     }
 *
 *
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ClientUserRepository")
 */
class ClientUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     * @Groups({"client_read", "client_write"})
     */

    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Client", inversedBy="clientUsers")
     */
    public $client;

    public function __construct()
    {
        $this->client = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClient(): Collection
    {
        return $this->client;
    }

    public function addClient(Client $client): self
    {
        if (!$this->client->contains($client)) {
            $this->client[] = $client;
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->client->contains($client)) {
            $this->client->removeElement($client);
        }

        return $this;
    }

    public function isUserOf(Client $client): bool
    {
        return $this->client->contains($client);
    }
}
