<?php

global $_config;

ini_set('display_errors', 1);
ini_set('error_reporting', -1);
ini_set('max_execution_time', 120);
ini_set("memory_limit", '256M');
date_default_timezone_set('America/Sao_Paulo');

if (!file_exists('config/config_ambiente.php')) {
    throw new \Exception('Arquivo de configuração do ambiente não encontrado');
}
if (!file_exists('config/config_db.php')) {
    throw new \Exception('Arquivo de configuração do banco de dados não encontrado');
}
if (file_exists('config/config_php.php')) {
    require 'config/config_php.php';
}

require 'config/config_ambiente.php';
require 'config/config_db.php';

$_config['secrety_key'] = '$2y$12$g/T0uuM/f1oYrHupl1/5c.Jxww0Gn7Gxk/XjQKFBteS3TKFu.uUpq';

$a = $_config['ambiente'];

if (!file_exists($_config[$a]['tmp_dir'])) {
    mkdir($_config[$a]['tmp_dir']);
}
if (!file_exists($_config[$a]['log_dir'])) {
    mkdir($_config[$a]['log_dir']);
}

@chmod($_config[$a]['tmp_dir'], 0777);
@chmod($_config[$a]['log_dir'], 0777);

define('TMP_DIR', $_config[$a]['tmp_dir']);
define('LOG_DIR', $_config[$a]['log_dir']);
define('SECRETY_KEY', $_config['secrety_key']);