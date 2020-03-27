<?php

class SessionCest
{
    public function _before(ApiTester $I)
    {
        $I->_before();
    }

    // tests
    public function blockWithInvalidEmail(ApiTester $I)
    {
        $form = [
            'email' => 'email_inexistente_666@tagtec.com.br',
            'password' => '123456',
        ];
        $I->sendPOST('/session', $form);
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => true,
            'message' => 'Usuário não encontrado',
        ]);
    }

    public function blockWithInvalidPassword(ApiTester $I)
    {
        $form = [
            'email' => 'mestre@tagtec.com.br',
            'password' => 'senha_errada',
        ];
        $I->sendPOST('/session', $form);
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => true,
            'message' => 'Senha inválida',
        ]);
    }

    public function correctLogin(ApiTester $I)
    {
        $email = 'mestre@tagtec.com.br';
        $form = [
            'email' => $email,
            'password' => '123456',
        ];
        $I->sendPOST('/session', $form);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'user' => ['email' => $email],
        ]);

        $I->seeResponseMatchesJsonType([
            'user' => [
                'id' => 'integer',
                'name' => 'string',
                'email' => 'string:email',
            ],
            'token' => 'string',
        ]);
    }

}
