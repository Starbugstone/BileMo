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
use App\Controller\ClientIntegration\UpdateClientPasswordAction;
use App\Controller\DeleteClientUserFromClientAction;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"client_read"}},
 *     itemOperations={
 *          "get"={
 *              "path"="/clients/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('SELF_AND_ADMIN', object)"
 *          },
 *          "put"={
 *              "path"="/clients/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('SELF_AND_ADMIN', object)",
 *              "denormalization_context"={"groups"={"client_write"}},
 *          },
 *          "delete"={
 *              "path"="/clients/{id}",
 *              "requirements"={"id"="\d+"},
 *              "security"="is_granted('ROLE_ADMIN')"
 *          },
 *          "put_ActivateClientPassword"={
 *              "method"="PUT",
 *              "path"="/clients/{id}/activate",
 *              "requirements"={"id"="\d+"},
 *              "controller"=ActivateClientPasswordAction::class,
 *              "denormalization_context"={"groups"={"activate_client"}},
 *              "validation_groups"={"Default", "update"}
 *          },
 *          "put_ResetClientPassword"={
 *              "method"="PUT",
 *              "path"="/clients/{id}/password/reset",
 *              "requirements"={"id"="\d+"},
 *              "controller"=ResetClientPasswordAction::class,
 *              "denormalization_context"={"groups"={"reset_client"}},
 *              "validation_groups"={"Default", "update"}
 *          },
 *          "delete_clientUser_from_client"={
 *              "method"="DELETE",
 *              "path"="/clients/{id}/users/{user_id}",
 *              "requirements"={"id"="\d+", "user_id"="\d+"},
 *              "controller"=DeleteClientUserFromClientAction::class,
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *     },
 *     collectionOperations={
 *           "get"={
 *              "path"="/clients",
 *          },
 *           "post"={
 *              "path"="/clients",
 *              "security"="is_granted('ROLE_ADMIN')",
 *              "denormalization_context"={"groups"={"client_create"}}
 *          },
 *          "post_UpdateMyPassword"={
 *              "method"="POST",
 *              "path"="/clients/self/password/update",
 *              "controller"=UpdateClientPasswordAction::class,
 *              "denormalization_context"={"groups"={"update_client_password"}},
 *              "validation_groups"={"Default", "update"},
 *              "cache_headers"={"max_age"=0}
 *          },
 *       },
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
     * @Groups({"client_read", "client_write", "client_create", "client_user_read"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     * @Groups({"admin_client_read", "admin_client_write"})
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
     * @Groups({"activate_client","reset_client","update_client_password"})
     * @Assert\NotBlank(groups={"update"})
     * @Assert\Length(min = 5,groups={"update"})
     */
    private $plainPassword;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ClientUser", mappedBy="client")
     * @Groups({"admin_client_read", "client_client_write", "client_read"})
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
     * @Groups({"client_read", "client_write", "client_create", "client_user_read"})
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"client_read", "admin_client_write"})
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

    /**
     * @param string $password
     * @return $this
     */
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
