<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functions_FunctionCalledWithOtherCase extends Analyzer {
    /* 3 methods */

    public function testFunctions_FunctionCalledWithOtherCase01()  { $this->generic_test('Functions_FunctionCalledWithOtherCase.01'); }
    public function testFunctions_FunctionCalledWithOtherCase02()  { $this->generic_test('Functions_FunctionCalledWithOtherCase.02'); }
    public function testFunctions_FunctionCalledWithOtherCase03()  { $this->generic_test('Functions_FunctionCalledWithOtherCase.03'); }
}
?>