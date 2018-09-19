<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class VariableUppercase extends Analyzer {
    /* 3 methods */

    public function testVariables_VariableUppercase01()  { $this->generic_test('Variables_VariableUppercase.01'); }
    public function testVariables_VariableUppercase02()  { $this->generic_test('Variables_VariableUppercase.02'); }
    public function testVariables_VariableUppercase03()  { $this->generic_test('Variables_VariableUppercase.03'); }
}
?>