<?php


namespace App\Tests\Controller;


use App\Entity\Phone;
use App\Entity\PhoneImage;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;

class CreatePhoneImageActionTest extends TestCase
{
    public function testDummy()
    {
        $this->assertEquals(1,1);
    }
//    public function testCreationPhoneImage()
//    {
//
//        $phone = new Phone();
//
//        $objectRepositoryMock = $this->createMock(ObjectRepository::class);
//        $objectRepositoryMock->expects($this->once())
//            ->method('find')
//            ->willReturn($phone);
//
//        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
//        $entityManagerMock->expects($this->once())
//            ->method('getRepository')
//            ->willReturn($objectRepositoryMock);
//
//        $requestStackMock = $this->getMockBuilder(Request::class)
//            ->disableOriginalConstructor()
//            ->setMethods(['get'])
//            ->getMock();
//    }
}