<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     attributes={"access_control"="is_granted('ROLE_CLIENT')"},
 *     collectionOperations={
 *         "get",
 *         "post"={"access_control"="is_granted('ROLE_CLIENT')"}
 *     },
 *     itemOperations={
 *         "get"={"access_control"="is_granted('ROLE_CLIENT') and object.isUserOf(user)", "access_control_message"="Sorry, not one of your users."},
 *         "put"={"access_control"="is_granted('ROLE_CLIENT') and object.isUserOf(user)", "access_control_message"="Sorry, you can only update your own users."},
 *     }
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
     * @ORM\Column(type="string", length=255)
     */
    private $username;

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

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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
