<?php

class Util
{

    public static function limparArquivosTemporarios(array $diretorios, DateInterval $dt_interval, $ignorados = ['.gitkeep', '.htaccess'])
    {
        $ignorados = array_merge($ignorados, ['.', '..']);
        $hoje = new DateTime();
        $hoje->sub($dt_interval);
        //$hoje->sub(new DateInterval('PT24H'));
        foreach ($diretorios as $dir) {
            if (is_dir($dir)) {
                if ($handle = opendir($dir)) {
                    while (false !== ($entry = readdir($handle))) {
                        if (!in_array($entry, $ignorados) && is_file($dir . DIRECTORY_SEPARATOR . $entry)) {
                            $a = $dir . DIRECTORY_SEPARATOR . $entry;
                            $dt = new DateTime();
                            $dt->setTimestamp(filectime($a));
                            if ($dt < $hoje) {
                                @unlink($a);
                            }
                        }
                    }
                    closedir($handle);
                }
            }
        }
    }
}