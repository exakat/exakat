<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class FunctioncallArray extends Tokenizer {
    /* 2 methods */

    public function testFunctioncallArray01()  { $this->generic_test('FunctioncallArray.01'); }
    public function testFunctioncallArray02()  { $this->generic_test('FunctioncallArray.02'); }
}
?>