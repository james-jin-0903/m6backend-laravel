<?php
namespace App;
use \Firebase\JWT\JWT;

class CognitoJWT {
    public static function verifyToken($jwt): ?object {
        try{
            // Fix for timestamp error.
            JWT::$leeway = 5;

            $possible_keys = [
                getenv('COGNITO_PUBLIC_KID_1') => str_replace("\\n", "\n", getenv('COGNITO_PUBLIC_KEY_1')),
                getenv('COGNITO_PUBLIC_KID_2') => str_replace("\\n", "\n", getenv('COGNITO_PUBLIC_KEY_2')),
            ];

            return JWT::decode($jwt, $possible_keys, ['RS256']);
        }catch(\Exception $e){
            return $e;
        }
    }
}
