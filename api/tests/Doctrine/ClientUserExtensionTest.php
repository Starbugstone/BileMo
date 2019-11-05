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

    protected function setUp():void
    {
        $this->securityMock = $this->createMock(Security::class);
        $this->queryBuilderMock = $this->createMock(QueryBuilder::class);
        $this->queryNameGeneratorMock = $this->createMock(QueryNameGeneratorInterface::class);
        $this->resourceClassMock = '';
    }

    protected function tearDown():void
    {
        $this->securityMock = null;
        $this->queryBuilderMock = null;
        $this->queryNameGeneratorMock = null;
        $this->resourceClassMock = null;
    }

    public function testAddWhereAsClient()
    {
        $user = new Client();

        $this->securityMock->expects($this->exactly(2))
            ->method('getUser')
            ->willReturn($user);

        $this->resourceClassMock = ClientUser::class;

        $this->queryBuilderMock->expects($this->exactly(2))
            ->method('getRootAliases')
            ->willreturn(["o"]);

        $this->queryBuilderMock->expects($this->exactly(2))
            ->method('andWhere')
            ->willreturn(true);

        $this->queryBuilderMock->expects($this->exactly(2))
            ->method('setParameter')
            ->willreturn(true);

        $this->executeItemAndCollection();
    }

    public function testAddWhereAdmin()
    {
        $this->securityMock->expects($this->exactly(2))
            ->method('isGranted')
            ->willReturn(true); //We are an Admin

        $this->resourceClassMock = ClientUser::class;

        $this->queryBuilderMock->expects($this->never())
            ->method('getRootAliases');

        $this->queryBuilderMock->expects($this->never())
            ->method('andWhere');

        $this->queryBuilderMock->expects($this->never())
            ->method('setParameter');

        $this->executeItemAndCollection();
    }

    public function testAddWhereNotClientUserClass()
    {
        $user = new ClientUser();

        $this->securityMock->expects($this->exactly(2))
            ->method('getUser')
            ->willReturn($user);

        $this->resourceClassMock = Client::class;

        $this->queryBuilderMock->expects($this->never())
            ->method('getRootAliases');

        $this->queryBuilderMock->expects($this->never())
            ->method('andWhere');

        $this->queryBuilderMock->expects($this->never())
            ->method('setParameter');

        $this->executeItemAndCollection();
    }

    public function testAddWhereNullClient()
    {
        $user = null;

        $this->securityMock->expects($this->exactly(2))
            ->method('getUser')
            ->willReturn($user);

        $this->resourceClassMock = Client::class;

        $this->queryBuilderMock->expects($this->never())
            ->method('getRootAliases');

        $this->queryBuilderMock->expects($this->never())
            ->method('andWhere');

        $this->queryBuilderMock->expects($this->never())
            ->method('setParameter');

        $this->executeItemAndCollection();
    }

    private function executeItemAndCollection()
    {
        $identifiers = [];

        $clientUserExtension = new ClientUsersExtension($this->securityMock);
        $clientUserExtension->applyToCollection($this->queryBuilderMock, $this->queryNameGeneratorMock,
            $this->resourceClassMock);

        $clientUserExtension->applyToItem($this->queryBuilderMock, $this->queryNameGeneratorMock,
            $this->resourceClassMock, $identifiers);
    }

}
