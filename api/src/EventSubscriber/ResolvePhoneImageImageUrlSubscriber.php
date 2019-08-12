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
//        var_dump($event);

        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if ($controllerResult instanceof Response || !$request->attributes->getBoolean('_api_respond', true)) {
            return;
        }

        //TODO: Also allow this when we are calling the actual phones so we get the URL back in the array
        if (!($attributes = RequestAttributesExtractor::extractAttributes($request)) || (!\is_a($attributes['resource_class'], PhoneImage::class, true) && !\is_a($attributes['resource_class'], Phone::class, true))) {
//            var_dump($attributes); //TODO: Ok, no longer here thanks to the || ( && ) for Phone class
//            die('here ');
            return;
        }

        $mediaObjects = $controllerResult;

        if (!is_iterable($mediaObjects)) {
            $mediaObjects = [$mediaObjects];
        }

        foreach ($mediaObjects as $mediaObject) {

            if (!$mediaObject instanceof PhoneImage) {
                //TODO: the Phone is not an instance of PhoneImage so we don't get our nice URL !
                continue;
            }

//            $mediaObject->imageUrl = $this->storage->resolveUri($mediaObject, 'imageFile');
            $mediaObject->setImageUrl($this->storage->resolveUri($mediaObject, 'imageFile'));
        }
    }

}
