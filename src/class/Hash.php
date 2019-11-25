<?php

// https://www.php.net/manual/pt_BR/function.password-hash.php
// https://www.php.net/manual/pt_BR/function.password-verify.php
class Hash
{

    public static function create($a, $alg = PASSWORD_BCRYPT)
    {
        return password_hash($a, $alg);
    }

    public static function verify($a, $hash)
    {
        return password_verify($a, $hash);
    }

}