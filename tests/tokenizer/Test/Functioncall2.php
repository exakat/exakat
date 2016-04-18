<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functioncall2 extends Tokenizer {
    /* 1 methods */

    public function testFunctioncall201()  { $this->generic_test('Functioncall2.01'); }
}
?>