<?php


namespace App\Tests\DataPersister;


use App\DataPersister\ClientDataPersister;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientDataPersisterTest extends TestCase
{

    private $managerMock;

    private $passwordEncoderMock;

    protected function setUp()
    {
        $this->managerMock = $this->createMock(EntityManagerInterface::class);
        $this->passwordEncoderMock = $this->createMock(UserPasswordEncoderInterface::class);
    }

    protected function tearDown()
    {
        $this->managerMock = null;
        $this->passwordEncoderMock = null;
    }

    public function testSupports()
    {
        $testClient = new Client();
        $testOther = new \stdClass();
        $testClientDataPersister = new ClientDataPersister($this->managerMock, $this->passwordEncoderMock);
        $this->assertTrue($testClientDataPersister->supports($testClient));
        $this->assertFalse($testClientDataPersister->supports($testOther));
    }

    public function testPersistEmptyPassword()
    {
        $testClient = new Client();
        $testClientDataPersister = new ClientDataPersister($this->managerMock, $this->passwordEncoderMock);
        $this->managerMock->expects($this->once())
            ->method('persist')
            ;
        $this->managerMock->expects($this->once())
            ->method('flush')
            ;

        $testClientDataPersister->persist($testClient);
    }

    public function testPersistWithPassword()
    {
        $testClient = new Client();
        $testClient->setPlainPassword('azerty');
        $testClientDataPersister = new ClientDataPersister($this->managerMock, $this->passwordEncoderMock);

        $this->managerMock->expects($this->once())
            ->method('persist')
            ;
        $this->managerMock->expects($this->once())
            ->method('flush')
            ;
        $this->passwordEncoderMock->expects($this->once())
            ->method('encodePassword')
            ->willReturn('123456')
            ;
        $testClientDataPersister->persist($testClient);
    }

    public function testRemove()
    {
        $testClient = new Client();
        $testClientDataPersister = new ClientDataPersister($this->managerMock, $this->passwordEncoderMock);
        $this->managerMock->expects($this->once())
            ->method('remove')
        ;
        $this->managerMock->expects($this->once())
            ->method('flush')
        ;

        $testClientDataPersister->remove($testClient);
    }
}