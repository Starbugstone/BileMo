<?php
// Api/Src/Entity/ForgotPasswordRequest.php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\ClientIntegration\ForgotClientPasswordAction;

/**
 * Class ForgotPasswordRequest
 * @package App\DTO
 * @ApiResource(
 *     itemOperations={},
 *     collectionOperations={
 *          "forgot_password"={
 *              "method"="POST",
 *              "path"="/clients/password/forgot",
 *              "controller"=ForgotClientPasswordAction::class,
 *          },
 *          "get"={
 *              "path"="/clients/password",
 *          }
 *     }
 *
 * )
 */
class ForgotPasswordRequest
{
    /**
     * @var string
     * @ApiProperty(identifier=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @param mixed $email
     * @return ForgotPasswordRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
}
