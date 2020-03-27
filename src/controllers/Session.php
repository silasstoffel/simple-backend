<?php

use Firebase\JWT\JWT;
use Psr\Container\ContainerInterface;

require_once './models/Usuario.php';

class SessionController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $email = isset($params['email']) && strlen(trim($params['email'])) ? trim($params['email']) : null;
        $pwd = isset($params['password']) && strlen($params['password']) ? $params['password'] : null;

        if (is_null($email) or is_null($pwd)) {
            return $response->withJson(['error' => true, 'message' => 'E-mail e senha são obrigatórios'], 401);
        }

        try {
            $user = R::findOne('user', 'email = ?', [$email]);
            if (!isset($user->id)) {
                return $response->withJson(['error' => true, 'message' => 'Usuário não encontrado'], 401);
            }
        } catch (\Exception $e) {
            return $response->withJson(['error' => true, 'message' => 'Erro ao encontrar usuário. Detalhe: ' . $e->getMessage()], 401);
        }

        // Senha é válida
        if (!Hash::verify($pwd, $user->password_hash)) {
            return $response->withJson(['error' => true, 'message' => 'Senha inválida'], 401);
        }
        $user = $user->export();
        unset($user['password_hash'], $user['created_at'], $user['updated_at']);

        $now = new DateTime();
        $future = new DateTime("now +8 hours");
        $data = ['iat' => $now->getTimeStamp(), 'exp' => $future->getTimeStamp(), 'id' => $user['id']];
        $token = JWT::encode($data, SECRETY_KEY, "HS256");
        $resp = ['user' => $user, 'token' => $token];
        return $response->withJson($resp);
    }

}
