<?php


namespace App\Tests\Token;


use App\Token\TokenGenerator;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class TokenGeneratorTest extends TestCase
{

    private $tokenGenerator;

    protected function setUp()
    {
        $this->tokenGenerator = new TokenGenerator();
    }

    protected function tearDown()
    {
        $this->tokenGenerator = null;
    }

    public function testTokenGenerator()
    {
        //as we are transforming to hex, the length is doubled
        $this->assertEquals(10,strlen($this->tokenGenerator->uniqueToken(5)), "unique token not length of 5");
        $this->assertEquals(40, strlen($this->tokenGenerator->uniqueToken()) , 'default token length not of 40');
    }
}