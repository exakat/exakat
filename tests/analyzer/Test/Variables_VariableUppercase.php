<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_VariableUppercase extends Analyzer {
    /* 2 methods */

    public function testVariables_VariableUppercase01()  { $this->generic_test('Variables_VariableUppercase.01'); }
    public function testVariables_VariableUppercase02()  { $this->generic_test('Variables_VariableUppercase.02'); }
}
?>