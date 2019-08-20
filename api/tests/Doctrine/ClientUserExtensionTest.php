<?php

namespace App\Tests\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Doctrine\ClientUsersExtension;
use App\Entity\Client;
use App\Entity\ClientUser;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Security\Core\Security;

class ClientUserExtensionTest extends TestCase
{

    private $securityMock;
    private $queryBuilderMock;
    private $queryNameGeneratorMock;
    private $resourceClassMock;

    protected function setUp()
    {
        $this->securityMock = $this->createMock(Security::class);
        $this->queryBuilderMock = $this->createMock(QueryBuilder::class);
        $this->queryNameGeneratorMock = $this->createMock(QueryNameGeneratorInterface::class);
        $this->resourceClassMock = '';
    }

    protected function tearDown()
    {
        $this->securityMock = null;
        $this->queryBuilderMock = null;
        $this->queryNameGeneratorMock = null;
        $this->resourceClassMock = null;
    }

    public function testAddWhereAsClient()
    {
        $user = new Client();

        $this->securityMock->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->resourceClassMock = ClientUser::class;

        $this->queryBuilderMock->expects($this->once())
            ->method('getRootAliases')
            ->willreturn(["o"]);

        $this->queryBuilderMock->expects($this->once())
            ->method('andWhere')
            ->willreturn(true);

        $this->queryBuilderMock->expects($this->once())
            ->method('setParameter')
            ->willreturn(true);


        $clientUserExtension = new ClientUsersExtension($this->securityMock);
        $clientUserExtension->applyToCollection($this->queryBuilderMock, $this->queryNameGeneratorMock,
            $this->resourceClassMock);
    }

    public function testAddWhereAdmin()
    {
        $this->securityMock->expects($this->once())
            ->method('isGranted')
            ->willReturn(true); //We are an Admin

        $this->resourceClassMock = ClientUser::class;

        $this->queryBuilderMock->expects($this->never())
            ->method('getRootAliases');

        $this->queryBuilderMock->expects($this->never())
            ->method('andWhere');

        $this->queryBuilderMock->expects($this->never())
            ->method('setParameter');


        $clientUserExtension = new ClientUsersExtension($this->securityMock);
        $clientUserExtension->applyToCollection($this->queryBuilderMock, $this->queryNameGeneratorMock,
            $this->resourceClassMock);
    }

    public function testAddWhereNotClientUserClass()
    {
        $user = new ClientUser();

        $this->securityMock->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->resourceClassMock = Client::class;

        $this->queryBuilderMock->expects($this->never())
            ->method('getRootAliases');

        $this->queryBuilderMock->expects($this->never())
            ->method('andWhere');

        $this->queryBuilderMock->expects($this->never())
            ->method('setParameter');


        $clientUserExtension = new ClientUsersExtension($this->securityMock);
        $clientUserExtension->applyToCollection($this->queryBuilderMock, $this->queryNameGeneratorMock,
            $this->resourceClassMock);
    }

    public function testAddWhereNullClient()
    {
        $user = null;

        $this->securityMock->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->resourceClassMock = Client::class;

        $this->queryBuilderMock->expects($this->never())
            ->method('getRootAliases');

        $this->queryBuilderMock->expects($this->never())
            ->method('andWhere');

        $this->queryBuilderMock->expects($this->never())
            ->method('setParameter');


        $clientUserExtension = new ClientUsersExtension($this->securityMock);
        $clientUserExtension->applyToCollection($this->queryBuilderMock, $this->queryNameGeneratorMock,
            $this->resourceClassMock);
    }

}