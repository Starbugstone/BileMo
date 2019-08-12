<?php

namespace App\Controller;

use App\Entity\PhoneImage;
use App\Entity\Phone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreatePhoneImageAction extends AbstractController
{


    public function __invoke(Request $request)
    {
        $uploadedFile = $request->files->get('imageFile');
        $repo = $this->getDoctrine()->getRepository(Phone::class);


        //TODO: Prehaps look into reformatting when we are passing the API url ?
        /**@var Phone $phone */
        $phone = $repo->find($request->get('phone'));


        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if(!$phone){
            throw new BadRequestHttpException('The Phone ID is incorrect');
        }

        $phoneImage = new PhoneImage();
        $phoneImage->setImageFile($uploadedFile);
        $phoneImage->setPhone($phone);

        return $phoneImage;
    }
}