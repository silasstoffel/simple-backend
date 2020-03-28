<?php

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class ApiTester extends \Codeception\Actor
{
    use _generated\ApiTesterActions;

    public $session = [];

    public function _before()
    {
        $this->haveHttpHeader('Content-Type', 'application/json');
        $this->haveHttpHeader('ACCEPT', 'application/json');
        $this->haveHttpHeader('X_REQUESTED_WITH', 'xmlhttprequest');
    }

    public function login()
    {
        $login = [
            'email' => 'mestre@tagtec.com.br',
            'password' => '123456',
        ];
        $this->sendPOST('/session', $login);
        $this->seeResponseCodeIs(200);
        $this->seeResponseIsJson();
        //
        $this->seeResponseContainsJson([
            'user' => [
                'email' => 'mestre@tagtec.com.br',
                'name' => 'Mestre TagTec',
            ],
        ]);

        $this->seeResponseMatchesJsonType([
            'user' => [
                'id' => 'integer:>0',
                'name' => 'string',
                'email' => 'string:email',
                'mobile_phone' => 'string|null',
            ],
            'token' => 'string',
        ]);

        $session = json_decode($this->grabResponse(), true);
        $this->session = $session;
        $this->amBearerAuthenticated($session['token']);
    }

    public function getCurrentSession()
    {
        return $this->session;
    }
}
