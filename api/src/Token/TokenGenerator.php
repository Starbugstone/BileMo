<?php
// /api\src\Token\TokenGenerator.php

namespace App\Token;


use Exception;
use http\Exception\RuntimeException;

class TokenGenerator
{
    /**
     * @param Int $length the length of the token
     * @return string the unique token
     * @throws RuntimeException
     */
    public function uniqueToken(Int $length = 20)
    {
        try{
            $random = random_bytes($length);
        } catch (Exception $exception){
            throw new RuntimeException('Random_bytes failed to generate a number for a token');
        }
        return bin2hex($random);
    }
}