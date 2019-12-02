<?php


namespace App\Tests\Controller;


use App\Controller\CreateClientUserAction;
use App\Entity\Client;
use App\Entity\ClientUser;
use App\Repository\ClientUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CreateClientUserActionTest extends TestCase
{

    protected $tokenStorageMock;
    protected $clientUserRepositoryMock;
    protected $entityManagerMock;

    protected function setUp():void
    {
//        $this->tokenStorageMock = $this->createMock(TokenStorageInterface::class);
        $this->tokenStorageMock = $this->getMockBuilder(TokenStorageInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(array('getToken', 'getUser', 'setToken'))
            ->getMock();
        $this->clientUserRepositoryMock = $this->createMock(ClientUserRepository::class);
        $this->entityManagerMock = $this->createMock(EntityManagerInterface::class);
    }

    protected function tearDown():void
    {
        $this->tokenStorageMock = null;
        $this->clientUserRepositoryMock = null;
        $this->entityManagerMock = null;
    }

    public function testCreateNewClient()
    {
        $client = new Client();
        $clientUser = new ClientUser();

        $client->setUsername('foo');
        $clientUser->setEmail('foo@bar.com');

        $this->clientUserRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $this->tokenStorageMock->expects($this->any())
            ->method('getToken')
            ->will($this->returnSelf());;

        $this->tokenStorageMock->expects($this->once())
            ->method('getUser')
            ->willReturn($client);


        $createClientUserAction = new CreateClientUserAction($this->tokenStorageMock, $this->clientUserRepositoryMock, $this->entityManagerMock);

        $data = $createClientUserAction($clientUser);
//        var_dump($data->getClient());
        $this->assertEquals($data, $clientUser);
        $this->assertEquals(1, count($data->getClient()));
        $this->assertContains($client, $data->getClient());

    }

    public function testCreateExistingClient()
    {
        $client = new Client();
        $client2 = new Client();
        $clientUser = new ClientUser();

        $client->setUsername('foo');
        $client2->setUsername('baz');
        $clientUser->setEmail('foo@bar.com');
        $clientUser->addClient($client2);

        $this->clientUserRepositoryMock->expects($this->once())
            ->method('findOneBy')
            ->willReturn($clientUser);

        $this->tokenStorageMock->expects($this->any())
            ->method('getToken')
            ->will($this->returnSelf());
        ;

        $this->tokenStorageMock->expects($this->once())
            ->method('getUser')
            ->willReturn($client);


        $createClientUserAction = new CreateClientUserAction($this->tokenStorageMock, $this->clientUserRepositoryMock, $this->entityManagerMock);

        $data = $createClientUserAction($clientUser);



        $this->assertEquals($data, $clientUser);
        $this->assertEquals(2, count($data->getClient()));
        $this->assertContains($client2, $data->getClient());
        $this->assertContains($client, $data->getClient());

    }
}
