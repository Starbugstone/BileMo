<?php
// api\src\Entity\Client.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Controller\ClientIntegration\ActivateClientPasswordAction;
use App\Controller\ClientIntegration\ResetClientPasswordAction;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"client_read"}},
 *     itemOperations={
 *          "get"={
 *              "access_control"="is_granted('SELF_AND_ADMIN', previous_object)"
 *          },
 *          "put"={
 *              "access_control"="is_granted('SELF_AND_ADMIN', previous_object)",
 *              "denormalization_context"={"groups"={"client_write"}}
 *          },
 *          "delete"={
 *              "access_control"="security('ROLE_ADMIN')"
 *          },
 *          "put_ActivateClientPassword"={
 *              "method"="PUT",
 *              "path"="/activate_client/{id}",
 *              "controller"=ActivateClientPasswordAction::class,
 *              "denormalization_context"={"groups"={"activate_client"}},
 *          },
 *
 *          "put_ResetClientPassword"={
 *              "method"="PUT",
 *              "path"="/reset_client_password/{id}",
 *              "controller"=ResetClientPasswordAction::class,
 *              "denormalization_context"={"groups"={"reset_client"}},
 *          },
 *     },
 *     collectionOperations={
 *		   "get",
 *		   "post"={
 *              "access_control"="security('ROLE_ADMIN')",
 *              "denormalization_context"={"groups"={"client_write"}}
 *          }
 *	   },
 *
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"client_read", "client_write"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"admin_read", "admin_write"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var integer|null the reset password date in unix format
     * @ORM\Column(type="integer", nullable=true)
     */
    private $passwordChangeDate;

    /**
     * @var string|null the unencrypted password
     * @Groups({"activate_client","reset_client","client_write"})
     */
    private $plainPassword;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClientUser", mappedBy="client")
     * @ApiSubresource
     * @Groups({"admin_read", "client_write"})
     */
    private $clientUsers;

    /**
     * @var string|null the user unique token
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"activate_client","reset_client"})
     */
    private $newUserToken;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"client_read", "client_write"})
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"client_read", "admin_write"})
     */
    private $active = false;

    public function __construct()
    {
        $this->clientUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_CLIENT
        $roles[] = 'ROLE_CLIENT';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole(string $role): self
    {
        $roles = $this->roles;
        $roles[] = $role;
        $this->roles = array_unique($roles);
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return Collection|ClientUser[]
     */
    public function getClientUsers(): Collection
    {
        return $this->clientUsers;
    }

    public function addClientUser(ClientUser $clientUser): self
    {
        if (!$this->clientUsers->contains($clientUser)) {
            $this->clientUsers[] = $clientUser;
            $clientUser->addClient($this);
        }

        return $this;
    }

    public function removeClientUser(ClientUser $clientUser): self
    {
        if ($this->clientUsers->contains($clientUser)) {
            $this->clientUsers->removeElement($clientUser);
            $clientUser->removeClient($this);
        }

        return $this;
    }

    /**
     * @param string $plainPassword
     * @return Client
     */
    public function setPlainPassword(string $plainPassword): Client
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @return string|null
     */
    public function getNewUserToken(): ?string
    {
        return $this->newUserToken;
    }

    /**
     * @param string|null $newUserToken
     * @return $this
     */
    public function setNewUserToken(?string $newUserToken): self
    {
        $this->newUserToken = $newUserToken;

        return $this;
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @param mixed $passwordChangeDate
     * @return Client
     */
    public function setPasswordChangeDate($passwordChangeDate)
    {
        $this->passwordChangeDate = $passwordChangeDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordChangeDate()
    {
        return $this->passwordChangeDate;
    }
}
