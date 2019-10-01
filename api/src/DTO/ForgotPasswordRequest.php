<?php
// Api/Src/DTO/ForgotPasswordRequest.php

namespace App\DTO;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ForgotPasswordRequest
 * @package App\DTO
 * @ApiResource(
 *     collectionOperations={
 *       "POST"={"route_name"="forgot_password"}
 *     }
 *
 * )
 */
final class ForgotPasswordRequest
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;
}
