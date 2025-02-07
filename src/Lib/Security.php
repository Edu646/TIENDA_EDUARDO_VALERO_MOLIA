<?php 

namespace Lib;

class Security
{
    final public static function encryptPassw(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    

    final public static function verifyPassw(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    final public static function secretKey(): string
    {
 
        return $_ENV['SECRET_KEY'];
 }


 final public static function generateToken(string $key, array $data): string
 {
     $time = strtotime(datetime:"now");
     $token = array(
         "iat" => $time,
         "exp" => $time + 3600,
         "data" => $data
     );

     return JWT::encode($token, $key, algorithm:"HS256");
 }

}