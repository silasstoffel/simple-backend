<?php
// https://codeception.com/docs/10-APITesting

/**
 * Escopo do teste
 * 1 - Criar usuário
 * 2 - Tentar criar um com e-mail ja existente
 * 3 - Impedir login com email que não existe
 * 4 - Impedir login com senha errada
 * 5 - Login correto
 * 6 - Ler os dados do usuário em acesso (Aqui precisa estar logado e passar token(JWT))
 * 7 - Alteração
 * 7.1 - Alteração dos dados (nome, senha)
 * 7.2 - Loga com novos dados alterados
 * 8 Deletar a conta
 */

class AccountCest
{
  private $initial_account = [
    'email' => 'contato@tagtec.com.br',
    'password' => '123456',
    'mobile_phone' => '27996354103',
    'name' => 'Contato TagTec',
  ];
  private $created_account = [];
  private $session = [];

  public function _before(ApiTester $I)
  {
    $I->_before();
  }

  public function create(ApiTester $I)
  {
    $I->sendPOST('/signup', $this->initial_account);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->seeResponseIsJson();
    $asserts = [
      'name' => $this->initial_account['name'],
      'email' => $this->initial_account['email'],
      'mobile_phone' => $this->initial_account['mobile_phone'],
      'active' => 1,
    ];
    $I->seeResponseContainsJson($asserts);
    $this->created_account = json_decode($I->grabResponse(), true);
  }

  /**
   * Tentar criar uma conta com mesmo dados da conta criada no
   * passo anterior
   */
  public function noCreateWithExistingEmail(ApiTester $I)
  {
    $I->sendPOST('/signup', $this->initial_account);
    $I->seeResponseCodeIs(400);
    $I->seeResponseIsJson();
    $asserts = [
      'error' => true,
      'message' => 'Usuário existente',
    ];
    $I->seeResponseContainsJson($asserts);
  }

  public function noLoginWithInvalidEmail(ApiTester $I)
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

  public function noLoginWithInvalidPassword(ApiTester $I)
  {
    $form = [
      'email' => 'mestre@tagtec.com.br',
      'password' => '#senha_errada#',
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
    $form = [
      'email' => $this->initial_account['email'],
      'password' => $this->initial_account['password'],
    ];
    $I->sendPOST('/session', $form);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson([
      'user' => [
        'email' => $this->initial_account['email'],
      ],
    ]);
    $I->seeResponseMatchesJsonType([
      'user' => [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string:email',
        'mobile_phone' => 'string|null',
        'active' => 'integer',
        'can_delete' => 'integer|string',
      ],
      'token' => 'string',
    ]);

    $this->session = json_decode($I->grabResponse(), true);
  }

  public function loadCurrentProfile(ApiTester $I)
  {
    $user_id = isset($this->session['user']['id']) ? $this->session['user']['id'] : 0;
    // Envia o token pois o endpoint exige autenticação
    $I->amBearerAuthenticated($this->session['token']);
    $I->sendGET('/v1/users/' . $user_id);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    $I->seeResponseIsJson();
    $I->seeResponseContainsJson([
      'email' => $this->initial_account['email'],
      'name' => $this->initial_account['name'],
    ]);
  }

  public function update(ApiTester $I)
  {
    $profile = [
      'name' => '#profile updated',
      'mobile_phone' => '27900000000',
      'password' => '654321',
    ];
    // Envia o token pois o endpoint exige autenticação
    $I->amBearerAuthenticated($this->session['token']);
    $I->sendPUT('/v1/users/update/me', $profile);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->seeResponseIsJson();
    $asserts = $profile;
    unset($asserts['password']);
    $I->seeResponseContainsJson($asserts);

    // Tenta logar pós alteração do perfil
    $form = [
      'email' => $this->initial_account['email'], // E-mail não foi mudado!
      'password' => $profile['password'], // Senha nova!
    ];
    $I->sendPOST('/session', $form);
    $I->seeResponseCodeIs(200);
    $I->seeResponseIsJson();
    // A resposta bate com novos dados do perfil?
    unset($profile['password']);
    $I->seeResponseContainsJson([
      'user' => $profile,
    ]);
    // Tipagem da resposta
    $I->seeResponseMatchesJsonType([
      'user' => [
        'id' => 'integer',
        'name' => 'string',
        'email' => 'string:email',
        'mobile_phone' => 'string|null',
        'active' => 'integer',
        'can_delete' => 'integer|string',
      ],
      'token' => 'string',
    ]);

    // Novo token
    list($token) = $I->grabDataFromResponseByJsonPath('$.token');
    $this->session['token'] = $token;
    // Volta com Login original (facilitar os testes)
    $profile = $this->initial_account;
    $I->amBearerAuthenticated($this->session['token']);
    $I->sendPUT('/v1/users/update/me', $profile);
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->seeResponseIsJson();
    unset($profile['password']);
    $I->seeResponseContainsJson($profile);
  }

  public function delete(ApiTester $I)
  {
    $I->amBearerAuthenticated($this->session['token']);
    $I->sendDELETE('/v1/users/delete/me');
    $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    $I->seeResponseIsJson();
    $asserts = $this->initial_account;
    unset($asserts['password']);
    $I->seeResponseContainsJson($asserts);
  }
}
