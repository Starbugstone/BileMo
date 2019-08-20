<?php


namespace App\Tests\Entity;


use App\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ClientTest extends TestCase
{
    private $client;

    protected function setUp()
    {
        $this->client = new Client();
    }

    protected function tearDown()
    {
        $this->client = null;
    }

    public function testRoleAttribute()
    {
        $this->assertClassHasAttribute('roles', Client::class);
    }

    public function testAddRole()
    {
        $expectedArray = ['ROLE_ADMIN', 'ROLE_CLIENT'];

        $this->client->addRole('ROLE_ADMIN');
        $this->assertCount(2, $this->client->getRoles());
        $this->assertEquals($this->client->getRoles(), $expectedArray, "\$canonicalize = true", 0.0, 10,
            true); //Ignoring the array order
    }

    public function testDefaultOnlyClient()
    {
        $expectedArray = ['ROLE_CLIENT'];

        $this->assertCount(1, $this->client->getRoles());
        $this->assertEquals($this->client->getRoles(), $expectedArray, "\$canonicalize = true", 0.0, 10,
            true); //Ignoring the array order
    }
}