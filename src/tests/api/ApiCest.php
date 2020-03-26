<?php

class ApiCest
{
    public function _before(ApiTester $I)
    {
      /*
      $this->haveHttpHeader('Content-Type', 'application/json');
      $this->haveHttpHeader('ACCEPT', 'application/json');
      $this->haveHttpHeader('X_REQUESTED_WITH', 'xmlhttprequest');
      */
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
      $I->sendGET('/enviroment');
      $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
      $I->seeResponseIsJson();
    }
}
