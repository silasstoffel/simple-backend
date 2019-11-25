<?php

use Psr\Container\ContainerInterface;

class UserController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function signUp($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $email = isset($params['email']) && strlen(trim($params['email'])) ? trim($params['email']) : null;
        $pwd = isset($params['password']) && strlen($params['password']) ? $params['password'] : null;
        $name = isset($params['name']) && strlen($params['name']) ? $params['name'] : null;
        $mobile_phone = isset($params['mobile_phone']) && strlen($params['mobile_phone']) ? $params['mobile_phone'] : null;

        if (is_null($email) or is_null($pwd) or is_null($name) or is_null($mobile_phone)) {
            return $response->withJson(['error' => true, 'message' => 'E-mail e senha são obrigatórios'], 400);
        }
        $user = null;
        try {
            $user = R::findOne('user', 'email = ?', [$email]);
            if (isset($user->id)) {
                return $response->withJson(['error' => true, 'message' => 'Usuário existente'], 400);
            }
            $hash = Hash::create($pwd);
            $user = R::dispense('user');
            $user->name = $name;
            $user->email = $email;
            $user->mobile_phone = $mobile_phone;
            $user->active = 1;
            $user->password_hash = $hash;
            $created_at = new DateTime();
            $user->created_at = $created_at->format('Y-m-d H:i:s');
            $user->updated_at = $created_at->format('Y-m-d H:i:s');
            $id = R::store($user);
            $user = R::load('user', $id)->export();
            unset($user['password_hash']);
            return $response->withJson($user);
        } catch (\Exception $e) {
            return $response->withJson(['error' => true, 'message' => 'Erro ao gravar usuario. Detalhe: ' . $e->getMessage()], 400);
        }
    }

    public function load($request, $response, $args)
    {
        $id = isset($args['id']) && (int) $args['id'] ? (int) $args['id'] : null;
        if (!$id) {
            return $response->withJson(['error' => true, 'message' => 'Argumentos inválido ou não encontrados'], 400);
        }

        $user = R::load('user', $id);
        if (!isset($user->id)) {
            return $response->withJson(['error' => true, 'message' => 'Registro não encontrado'], 400);
        }
        $user = $user->export();
        unset($user['password_hash']);
        return $response->withJson($user);
    }

    public function updateMe($request, $response, $args)
    {
        $id = $this->container['user']['id'];
        if (!$id) {
            return $response->withJson(['error' => true, 'message' => 'Não foi possível identificar o usuário pelo token'], 400);
        }

        $params = $request->getParsedBody();
        $pwd = isset($params['password']) && strlen($params['password']) ? $params['password'] : null;
        $name = isset($params['name']) && strlen($params['name']) ? $params['name'] : null;
        $mobile_phone = isset($params['mobile_phone']) && strlen($params['mobile_phone']) ? $params['mobile_phone'] : null;

        if (is_null($name)) {
            return $response->withJson(['error' => true, 'message' => 'Nome é obrigatórios'], 400);
        }

        $user = R::load('user', $id);
        if (!isset($user->id)) {
            return $response->withJson(['error' => true, 'message' => 'Usuário não encontrado'], 400);
        }

        $user->name = $name;
        $user->mobile_phone = $mobile_phone;
        if ($pwd) {
            $user->password_hash = Hash::create($pwd);
        }
        $updated_at = new DateTime();
        $user->updated_at = $updated_at->format('Y-m-d H:i:s');
        R::store($user);
        unset($user->password_hash);
        return $response->withJson($user->export());
    }

}