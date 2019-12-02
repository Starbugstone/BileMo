<?php
// api\src\Controller\CreatePhoneImageAction.php

namespace App\Controller;

use App\Entity\PhoneImage;
use App\Entity\Phone;
use App\Repository\PhoneRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreatePhoneImageAction
{

    /**
     * @var PhoneRepository
     */
    private $repository;

    public function __construct(PhoneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Request $request): PhoneImage
    {
        $uploadedFile = $request->files->get('imageFile');

        /**@var Phone $phone */
        $phone = $this->repository->find($request->get('phone'));

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if ($phone === null) {
            throw new BadRequestHttpException('The Phone ID is incorrect');
        }

        $phoneImage = new PhoneImage();
        $phoneImage->setImageFile($uploadedFile);
        $phoneImage->setPhone($phone);
        $phoneImage->setTitle($request->get('title'));

        return $phoneImage;
    }
}
