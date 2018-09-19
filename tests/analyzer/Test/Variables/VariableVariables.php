<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class VariableVariables extends Analyzer {
    /* 1 methods */

    public function testVariables_VariableVariables01()  { $this->generic_test('Variables_VariableVariables.01'); }
}
?>