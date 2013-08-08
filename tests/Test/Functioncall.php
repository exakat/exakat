<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functioncall extends Tokenizeur {
    /* 44 methods */

    public function testFunctioncall44()  { $this->generic_test('Functioncall.44'); }
}
?>