<?php

class Pessoa
{
    public static function criarFotoTemporaria(array $pessoa)
    {
        if (!isset($pessoa['pessoa_id'])) {
            throw new Exception('Argumento pessoa_id não existe');
        }
        $arquivo = null;
        try {
            $cnx = DbConnection::getInstance();
            if ((int) $pessoa['fk_arquivo_upload_id']) {
                $stmt = $cnx->prepare('SELECT arquivo_upload_id, arquivo_upload_extensao, arquivo_upload_binario FROM arquivo_upload WHERE arquivo_upload_id = ?');
                $stmt->execute([$pessoa['fk_arquivo_upload_id']]);
                $upload = $stmt->fetch();
                if (isset($upload['arquivo_upload_id'])) {
                    $nome = $upload['arquivo_upload_id'] . '.' . $upload['arquivo_upload_extensao'];
                    $f = TMP_DIR . '/' . $nome;
                    @file_put_contents($f, $upload['arquivo_upload_binario']);
                    if (file_exists($f)) {
                        $arquivo = $nome;
                    }
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $arquivo;
    }
}