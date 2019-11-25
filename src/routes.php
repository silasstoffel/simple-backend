<?php

global $_app, $_config;

require_once 'controllers/Session.php';
require_once 'controllers/Ambiente.php';
require_once 'controllers/Home.php';
require_once 'controllers/User.php';

// Checagem do ambiente
$_app->get('/enviroment', \AmbienteController::class . ':index');
$_app->post('/enviroment/post', \AmbienteController::class . ':post');
$_app->put('/enviroment/put', \AmbienteController::class . ':put');

// Auth
$_app->post('/session', \SessionController::class . ':create');

// Sign Up
$_app->post('/signup', \UserController::class . ':signUp');

// Rotas autenticas por JWT
$_app->group('/v1', function (\Slim\App $app) use ($_config) {

    $app->get('/dashboard', \HomeController::class . ':index');

    // Users
    $app->get('/users/{id}', \UserController::class . ':load');
    $app->put('/users/update-me', \UserController::class . ':updateMe');

});
