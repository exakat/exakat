<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Methocall extends Tokenizer {
    /* 1 methods */

    public function testMethocall01()  { $this->generic_test('Methocall.01'); }
}
?>