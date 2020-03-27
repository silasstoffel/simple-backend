<?php

use Psr\Container\ContainerInterface;

class AmbienteController
{
    protected $container;

    // constructor receives container instance
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function index($request, $response, $args)
    {
        global $_config;
        $a = $_config['ambiente'];
        $request_info = $request->getUri();
        $base_url = $request_info->getScheme() . '://' . $request_info->getHost();
        if ($request_info->getPort() && $request_info->getPort() !== 80) {
            $base_url .= ':' . $request_info->getPort();
        }
        $conected_db = null;
        try {
          $conected_db = R::count('user');
        } catch(\Exception $e) {}

        $data = [
            'env' => $a,
            'base_url' => $base_url,
            'php_version' => PHP_VERSION,
            'config_php' => file_exists('config/config_php.php'),
            'config_ambiente' => file_exists('config/config_ambiente.php'),
            'config_db' => file_exists('config/config_db.php'),
            'always_populate_raw_post_data' => ini_get('always_populate_raw_post_data'),
            'conected_db' => is_numeric($conected_db),
            'dirs' => [
                'root' => $_config['root_dir'],
                'tmp' => TMP_DIR,
                'log' => LOG_DIR,
            ],
        ];
        return $response->withJson($data);
    }

    public function put($request, $response, $args)
    {
        $payload = $request->getParsedBody();
        $resp = [
            'payload' => $payload,
            'ping' => 'pong',
            'method' => 'put',
        ];
        return $response->withJson($resp);
    }

    public function post($request, $response, $args)
    {
        $payload = $request->getParsedBody();
        $resp = [
            'payload' => $payload,
            'ping' => 'pong',
            'method' => 'post',
        ];
        return $response->withJson($resp);
    }

}
