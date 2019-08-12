<?php

namespace App\Controller;

use App\Entity\PhoneImage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreatePhoneImageAction
{


    public function __invoke(Request $request)
    {
        $uploadedFile = $request->files->get('imageFile');

//        var_dump($request);
//        die();
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $phoneImage = new PhoneImage();
        $phoneImage->setImageFile($uploadedFile);

        return $phoneImage;
    }
}