<?php

namespace App\Controller;

use App\Entity\PhoneImage;
use App\Entity\Phone;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreatePhoneImageAction
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ObjectRepository
     */
    private $objectRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(Phone::class);
    }

    public function __invoke(Request $request)
    {
        $uploadedFile = $request->files->get('imageFile');
//        $repo = $this->getDoctrine()->getRepository(Phone::class);


        //TODO: Prehaps look into reformatting when we are passing the API url ?
        /**@var Phone $phone */
        $phone = $this->objectRepository->find($request->get('phone'));


        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        if($phone === null){
            throw new BadRequestHttpException('The Phone ID is incorrect');
        }

        $phoneImage = new PhoneImage();
        $phoneImage->setImageFile($uploadedFile);
        $phoneImage->setPhone($phone);

        return $phoneImage;
    }
}