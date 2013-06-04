<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Sign extends Tokenizeur {
    /* 1 methods */
    public function testSign01()  { $this->generic_test('Sign.01'); }
}
?>