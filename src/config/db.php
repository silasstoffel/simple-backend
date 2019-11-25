<?php

global $_config;
$amb = $_config['ambiente'];
$db = $_config['database'][$amb];
$dsn = sprintf($db['dsn'], $db['server'], $db['database']);
try {
    R::setup($dsn, $db['user'], $db['pass']);
    R::freeze(true);
} catch (Exception $exc) {
    throw $exc;
}
