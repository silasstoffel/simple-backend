<?php

class ApiCest
{
    public function _before(ApiTester $I)
    {
        $I->_before();
    }

    public function isOnline(ApiTester $I)
    {
        $I->sendGET('/enviroment');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'config_php' => true,
            'config_ambiente' => true,
            'config_db' => true,
            'conected_db' => true,
        ]);
    }
}
