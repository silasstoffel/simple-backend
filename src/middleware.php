<?php

use Tuupola\Middleware\JwtAuthentication;

global $_config, $_app, $_user;

// Habilitando o CORS
$_app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$container = $_app->getContainer();
$container['user'] = null;
$container["jwt"] = function ($container) {
    return new StdClass;
};

$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $response->withJson([
            'error' => true,
            'message' => 'Serviço não encontrado',
        ], 404);
    };
};

$container['JwtAuthentication'] = function ($container) use ($_config, $_user) {
    return new \Slim\Middleware\JwtAuthentication([
        'secret' => $_config['secrety_key'],
        "rules" => [
            new \Slim\Middleware\JwtAuthentication\RequestPathRule([
                "path" => "/",
                "passthrough" => ['/session', '/signup', "/enviroment", "/enviroment/post", "/enviroment/put"],
            ]),
        ],
        'attribute' => false,
        'error' => function ($request, $response, $arguments) {
            return $response->withJson(['error' => true, 'message' => 'Token inválido ou inexistente'], 401);
        },
        'callback' => function ($request, $response, $arguments) use ($container) {
            $container['jwt'] = $arguments['decoded'];
            $id = $container['jwt']->id;
            $user = R::load('user', $id);
            if (isset($user->id)) {
                unset($user->password_hash);
                $container['user'] = $user->export();
                $_user = $container['user'];
            }
        },
    ]);
};

$_app->add('JwtAuthentication');