<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Phone;
use App\Entity\PhoneImage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

final class ResolvePhoneImageImageUrlSubscriber implements EventSubscriberInterface
{

    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['onPreSerialize', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function onPreSerialize(GetResponseForControllerResultEvent $event): void
    {

        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) ) {
            return;
        }

        if (!\is_a($attributes['resource_class'],PhoneImage::class, true) && !\is_a($attributes['resource_class'], Phone::class, true)) {
            return;
        }

        $mediaObjects = $controllerResult;

        if (!is_iterable($mediaObjects)) {
            $mediaObjects = [$mediaObjects];
        }

        foreach ($mediaObjects as $mediaObject) {


            if (!$mediaObject instanceof PhoneImage && !$mediaObject instanceof Phone) {
                continue;
            }

            if ($mediaObject instanceof Phone) {
                $this->setPhoneImagesUrl($mediaObject);
            } else {
                $this->setImageUrl($mediaObject);
            }


        }
    }

    private function setPhoneImagesUrl(Phone $phone): void
    {
        $images = $phone->getPhoneImages();
        foreach ($images as $image) {
            $this->setImageUrl($image);
        }
    }

    private function setImageUrl(PhoneImage $phoneImage): void {
        $phoneImage->setImageUrl($this->storage->resolveUri($phoneImage, 'imageFile'));
    }


}
