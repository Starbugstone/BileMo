<?php


namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

Trait userAuthTrait
{
    public function authUser(string $user, string $pass): Client
    {
        $client = ApiTestCase::createClient();
        $response = $client->request('POST', '/client_login', [
            'json' => [
                'username' => $user,
                'password' => $pass
            ]
        ]);
        $obj = json_decode($response->getContent());

        return ApiTestCase::createClient([], [
            'headers' => ['Authorization' => 'bearer ' . $obj->token]
        ]);
    }
}
