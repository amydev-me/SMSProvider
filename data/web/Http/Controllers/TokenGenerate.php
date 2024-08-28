<?php
/**
 * Created by PhpStorm.
 * User: teddy
 * Date: 7/3/18
 * Time: 10:56 AM
 */

namespace Web\Http\Controllers;


use Firebase\JWT\JWT;

class TokenGenerate
{

    /**
     * Generate Token
     *
     * @return string encode
     */
    public function generateSecret()
    {
        // User->id
        //User->username
        //User->email
        //Scope-> user-role
        //LF@dmin!*
        //TOKEN_SECRET

        //username
        //useremail





        $token = (bin2hex(openssl_random_pseudo_bytes(24)));
        return JWT::urlsafeB64Encode($token);
    }
}