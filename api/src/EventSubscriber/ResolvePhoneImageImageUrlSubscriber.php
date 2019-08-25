<?php
// api\src\EventSubscriber\ResolvePhoneImageImageUrlSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Phone;
use App\Entity\PhoneImage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

final class ResolvePhoneImageImageUrlSubscriber implements EventSubscriberInterface
{

    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(StorageInterface $storage, RequestStack $requestStack)
    {
        $this->storage = $storage;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(ViewEvent $event): void
    {

        if (!$this->isCorrectCall($event)) {
            return;
        }

        $mediaObjects = $event->getControllerResult();

        if (!is_iterable($mediaObjects)) {
            $mediaObjects = [$mediaObjects];
        }

        foreach ($mediaObjects as $mediaObject) {

            if (!$mediaObject instanceof PhoneImage && !$mediaObject instanceof Phone) {
                continue;
            }

            if ($mediaObject instanceof Phone) {
                $this->setPhoneImagesUrl($mediaObject);
                continue;
            }

            $this->setImageUrl($mediaObject);


        }
    }

    /**
     * Go through each image on the phone and set the url
     * @param Phone $phone
     */
    private function setPhoneImagesUrl(Phone $phone): void
    {
        $images = $phone->getPhoneImages();
        foreach ($images as $image) {
            $this->setImageUrl($image);
        }
    }

    /**
     * Sets the full path of the phone image for ease of use
     * @param PhoneImage $phoneImage
     */
    private function setImageUrl(PhoneImage $phoneImage): void
    {
        $httpHost = $this->requestStack->getMasterRequest()->getSchemeAndHttpHost();
        $imagePath = $this->storage->resolveUri($phoneImage, 'imageFile');
        $phoneImage->setImageUrl($httpHost.$imagePath);
    }

    /**
     * checks if this is the right kind of call. Eg a request with Phone or PhoneImage,
     * @param ViewEvent $event
     * @return bool
     */
    private function isCorrectCall(ViewEvent $event): bool
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return false;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request))) {
            return false;
        }

        if (!\is_a($attributes['resource_class'], PhoneImage::class, true) && !\is_a($attributes['resource_class'],
                Phone::class, true)) {
            return false;
        }

        return true;
    }
}
