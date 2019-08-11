<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\PhoneImage;
use App\Form\PhoneImageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UploadPhoneImageAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function __invoke(Request $request): PhoneImage
    {
        //Create a new image
        $phoneImage = new PhoneImage();

        //validate the form
        $form = $this->formFactory->create(PhoneImageType::class, $phoneImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //persist the new phone image entity
            $this->entityManager->persist($phoneImage);
            $this->entityManager->flush();

            $phoneImage->setImageFile(null); //set to null before return to not return the full binary

            return $phoneImage;
        }

        //Trow a validation exception, something went wrong in the form validation
        throw new ValidationException(
            $this->validator->validate($phoneImage)
        );

//        $uploadedImage = $request->files->get('file');
//        if(!$uploadedImage){
//            throw new BadRequestHttpException('"file" is required');
//        }
//
//        $phoneImage = new PhoneImage();
//        $phoneImage->setImageFile($uploadedImage);
//
//        return $phoneImage;
    }
}