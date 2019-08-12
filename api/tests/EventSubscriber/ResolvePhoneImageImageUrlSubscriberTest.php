<?php


namespace App\Tests\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\PhoneImage;
use App\EventSubscriber\ResolvePhoneImageImageUrlSubscriber;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

class ResolvePhoneImageImageUrlSubscriberTest extends TestCase
{
    public function testConfiguration()
    {
        //Making sure we have the required events and calls
        $result = ResolvePhoneImageImageUrlSubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals(['onPreSerialize', EventPriorities::PRE_SERIALIZE], $result[KernelEvents::VIEW]);
    }

//    public function testSetImageUrlCallOnPhoneImage()
//    {
//        $phoneImage = new PhoneImage();
//        $phoneImage->setImage('abc.jpg');
//
//        $eventMock = $this->getMockBuilder(GetResponseForControllerResultEvent::class)
//            ->getMockForAbstractClass();
//
//        $eventMock->expects($this->atLeastOnce())
//            ->method('getControllerResult')
//            ->willReturn($phoneImage);
//
//
//        $storageMock = $this->getMockBuilder(StorageInterface::class)
//            ->getMockForAbstractClass();
//
//        $storageMock->expects($this->atLeastOnce())
//            ->method('resolveUri')
//            ->willReturn('/phone_images/abc.jpg');
//    }
}