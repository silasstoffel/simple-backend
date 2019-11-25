<?php

class Usuario
{
    public static function obterUsuarioPorEmail($email)
    {
        $c = "SELECT * FROM user WHERE email = ?";
        try {
            $cnx = DbConnection::getInstance();
            $stmt = $cnx->prepare($c);
            $stmt->execute([$email]);
            $usuario = $stmt->fetch();
            return $usuario;
        } catch (\Exception $th) {
            throw $th;
        }
        return null;
    }

}