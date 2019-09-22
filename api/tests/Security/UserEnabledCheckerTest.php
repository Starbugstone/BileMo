<?php


namespace App\Tests\Security;


use App\Entity\Client;
use App\Security\UserEnabledChecker;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Security\Core\Exception\DisabledException;

class UserEnabledCheckerTest extends TestCase
{
    /**
     * test if the client is not active
     */
    public function testIsNotActive()
    {
        $client = new Client();
        $client->setActive(false);

        $this->expectException(DisabledException::class);

        $userEnabledChecker = new UserEnabledChecker();
        $userEnabledChecker->checkPreAuth($client);
    }

}