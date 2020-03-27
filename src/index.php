<?php
// https://github.com/tuupola/slim-jwt-auth/tree/2.x
// https://github.com/tuupola/slim-api-skeleton
error_reporting(E_ALL);
$_config = [];
$_user = null;

require 'vendor/autoload.php';
require 'functions.php';
set_error_handler('appErrorHandler');
set_exception_handler('appExceptionHandler');
require 'class/RedBean.php';
require 'class/Hash.php';
require 'config/config.php';
require 'config/db.php';
require 'class/Util.php';
require 'class/Models.php';

$cfg = ['settings' => [
    'addContentLengthHeader' => false,
    'displayErrorDetails' => true,
]];

$_app = new \Slim\App($cfg);

require 'middleware.php';
require 'routes.php';

$_app->run();

// Limpa arquivos a cada 24 horas de existencia (PT24H)
\Util::limparArquivosTemporarios(
    [TMP_DIR],
    new DateInterval('PT24H'),
    ['.gitkeep', '.htaccess']
);
