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

    public function _before()
    {
        $this->haveHttpHeader('Content-Type', 'application/json');
        $this->haveHttpHeader('ACCEPT', 'application/json');
        $this->haveHttpHeader('X_REQUESTED_WITH', 'xmlhttprequest');
    }

    public function authenticate()
    {

    }
}
