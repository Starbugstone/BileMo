<?php


namespace App\Tests\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Phone;
use App\Entity\PhoneImage;
use App\EventSubscriber\ResolvePhoneImageImageUrlSubscriber;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Vich\UploaderBundle\Storage\StorageInterface;

class ResolvePhoneImageImageUrlSubscriberTest extends TestCase
{

    private $storageInterfaceMock;
    private $requestStackMock;
    private $viewEventMock;

    protected function setUp()
    {
        $this->storageInterfaceMock = $this->createMock(StorageInterface::class);
        $this->requestStackMock = $this->createMock(RequestStack::class);
        $this->viewEventMock = $this->createMock(ViewEvent::class);
    }

    protected function tearDown()
    {
        $this->storageInterfaceMock = null;
        $this->requestStackMock = null;
        $this->viewEventMock = null;
    }

    public function testConfiguration()
    {
        //Making sure we have the required events and calls
        $result = ResolvePhoneImageImageUrlSubscriber::getSubscribedEvents();
        $this->assertArrayHasKey(KernelEvents::VIEW, $result);
        $this->assertEquals(['onPreSerialize', EventPriorities::PRE_SERIALIZE], $result[KernelEvents::VIEW]);
    }

    public function testSetImageUrlCallOnPhoneImage()
    {
        $phoneImage = new PhoneImage();
        $phoneImage->setImage('abc.jpg');

        $subscriber = new ResolvePhoneImageImageUrlSubscriber($this->storageInterfaceMock, $this->requestStackMock);

        $controllerResultMock = $this->createMock(Request::class); //has to be a request

        $this->viewEventMock->expects($this->exactly(2))
            ->method('getControllerResult')
            ->willReturn($controllerResultMock);

        $this->viewEventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($controllerResultMock);

        $controllerResultMock->attributes['_api_resource_class'] = true;//Setting the resource class

        $paramBag = new ParameterBag([
            "_api_respond" => true,
            "_api_resource_class" => $phoneImage, // for the extractAttributes
            "_api_item_operation_name" => "dummy",
        ]);
        $controllerResultMock->attributes = $paramBag;

        // with this we know we pass into the mediaObject with at least one phoneImage object
        // If something is wrong, counting on PHP to throw an error as we can't test any returns
        $controllerResultMock = [$controllerResultMock]; //transforming into array
        $controllerResultMock[] = $phoneImage; //the second is a phone image
        $subscriber->onPreSerialize($this->viewEventMock);
    }

    public function testSetImageUrlCallOnPhone()
    {

        $phone = new Phone();
        $phone->setName('dummy');

        $phoneImage = new PhoneImage();
        $phoneImage->setImage('abc.jpg');
        $phoneImage->setPhone($phone);

        $phoneImage2 = new PhoneImage();
        $phoneImage2->setImage('abc.jpg');
        $phoneImage2->setPhone($phone);


        $subscriber = new ResolvePhoneImageImageUrlSubscriber($this->storageInterfaceMock, $this->requestStackMock);

        $controllerResultMock = $this->createMock(Request::class); //has to be a request

        $this->viewEventMock->expects($this->exactly(2))
            ->method('getControllerResult')
            ->willReturn($controllerResultMock);

        $this->viewEventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($controllerResultMock);

        $controllerResultMock->attributes['_api_resource_class'] = true;//Setting the resource class

        $paramBag = new ParameterBag([
            "_api_respond" => true,
            "_api_resource_class" => $phone, // for the extractAttributes
            "_api_item_operation_name" => "dummy",
        ]);
        $controllerResultMock->attributes = $paramBag;

        // with this we know we pass into the mediaObject with at least one phone object with 2 images
        // If something is wrong, counting on PHP to throw an error as we can't test any returns
        $controllerResultMock = [$controllerResultMock]; //transforming into array
        $controllerResultMock[] = $phone; //the second is a phone image
        $subscriber->onPreSerialize($this->viewEventMock);
    }

    public function testFailOnControllerResultResponse()
    {
        $phoneImage = new PhoneImage();
        $phoneImage->setImage('abc.jpg');

        $subscriber = new ResolvePhoneImageImageUrlSubscriber($this->storageInterfaceMock, $this->requestStackMock);

        $controllerResultMock = $this->createMock(Response::class); //has to be a request

        $this->viewEventMock->expects($this->once())//we exit before the second call
        ->method('getControllerResult')
            ->willReturn($controllerResultMock);

        $this->viewEventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($controllerResultMock);

        $controllerResultMock->attributes['_api_resource_class'] = true;//Setting the resource class

        $paramBag = new ParameterBag([
            "_api_respond" => true,
            "_api_resource_class" => $phoneImage, // for the extractAttributes
            "_api_item_operation_name" => "dummy",
        ]);
        $controllerResultMock->attributes = $paramBag;

        $subscriber->onPreSerialize($this->viewEventMock);
    }

    public function testFailOnAttributesNotBoolean()
    {
        $phoneImage = new PhoneImage();
        $phoneImage->setImage('abc.jpg');

        $subscriber = new ResolvePhoneImageImageUrlSubscriber($this->storageInterfaceMock, $this->requestStackMock);

        $controllerResultMock = $this->createMock(Request::class); //has to be a request

        $this->viewEventMock->expects($this->once())//we exit before the second call
        ->method('getControllerResult')
            ->willReturn($controllerResultMock);

        $this->viewEventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($controllerResultMock);

        $controllerResultMock->attributes['_api_resource_class'] = true;//Setting the resource class

        $paramBag = new ParameterBag([
            "_api_respond" => 'not a bool',
            "_api_resource_class" => $phoneImage, // for the extractAttributes
            "_api_item_operation_name" => "dummy",
        ]);
        $controllerResultMock->attributes = $paramBag;

        $subscriber->onPreSerialize($this->viewEventMock);
    }

    public function testFailOnRequestAttributesExtractor()
    {
        $phoneImage = new PhoneImage();
        $phoneImage->setImage('abc.jpg');

        $subscriber = new ResolvePhoneImageImageUrlSubscriber($this->storageInterfaceMock, $this->requestStackMock);

        $controllerResultMock = $this->createMock(Request::class); //has to be a request

        $this->viewEventMock->expects($this->once())//we exit before the second call
        ->method('getControllerResult')
            ->willReturn($controllerResultMock);

        $this->viewEventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($controllerResultMock);

        $controllerResultMock->attributes['_api_resource_class'] = true;//Setting the resource class

        $paramBag = new ParameterBag([
            "_api_respond" => true,
            "_api_item_operation_name" => "dummy",
        ]);
        $controllerResultMock->attributes = $paramBag;

        $subscriber->onPreSerialize($this->viewEventMock);
    }

    public function testFailOnNotPhoneImage()
    {
        $phoneImage = new \stdClass();

        $subscriber = new ResolvePhoneImageImageUrlSubscriber($this->storageInterfaceMock, $this->requestStackMock);

        $controllerResultMock = $this->createMock(Request::class); //has to be a request

        $this->viewEventMock->expects($this->once())//we exit before the second call
        ->method('getControllerResult')
            ->willReturn($controllerResultMock);

        $this->viewEventMock->expects($this->once())
            ->method('getRequest')
            ->willReturn($controllerResultMock);

        $controllerResultMock->attributes['_api_resource_class'] = true;//Setting the resource class

        $paramBag = new ParameterBag([
            "_api_respond" => true,
            "_api_resource_class" => $phoneImage, // for the extractAttributes
            "_api_item_operation_name" => "dummy",
        ]);
        $controllerResultMock->attributes = $paramBag;

        $subscriber->onPreSerialize($this->viewEventMock);
    }
}