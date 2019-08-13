<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use App\Entity\Phone;
use App\Entity\PhoneImage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
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

    public function onPreSerialize(GetResponseForControllerResultEvent $event): void
    {

        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        //TODO: Move this to private method to ease mocks
        ///---- Extract to method to mock after
        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) ) {
            return;
        }

        if (!\is_a($attributes['resource_class'],PhoneImage::class, true) && !\is_a($attributes['resource_class'], Phone::class, true)) {
            return;
        }
        ///--- end extract

        $mediaObjects = $controllerResult;

        if (!is_iterable($mediaObjects)) {
            $mediaObjects = [$mediaObjects];
        }

        foreach ($mediaObjects as $mediaObject) {


            //TODO: Check if we ever arrive here.
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

    private function setPhoneImagesUrl(Phone $phone): void
    {
        $images = $phone->getPhoneImages();
        foreach ($images as $image) {
            $this->setImageUrl($image);
        }
    }

    private function setImageUrl(PhoneImage $phoneImage): void {
        $httpHost = $this->requestStack->getMasterRequest()->getHttpHost();
        $imagePath = $this->storage->resolveUri($phoneImage, 'imageFile');
        $phoneImage->setImageUrl($httpHost.$imagePath);
    }


}
