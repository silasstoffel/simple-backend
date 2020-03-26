<?php

function appErrorHandler($severity, $message, $file, $line)
{
    if (error_reporting() & $severity) {
        ob_get_clean();
        $info = [
            'error' => true,
            'message' => $message . ' - ' . $file . ':' . $line,
            'file' => $file,
            'line' => $line,
        ];
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode($info, JSON_PRETTY_PRINT);
        exit(1);
    }
}

function appExceptionHandler(Error $exc)
{
    if (error_reporting()) {
        ob_get_clean();
        $info = [
            'error' => true,
            'message' => $exc->getMessage() . ' - ' . $exc->getFile() . ':' . $exc->getLine(),
            'file' => $exc->getFile(),
            'line' => $exc->getLine(),
        ];
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode($info, JSON_PRETTY_PRINT);
        exit(1);
    }
}
