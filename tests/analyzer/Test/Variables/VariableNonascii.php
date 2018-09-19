<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class VariableNonascii extends Analyzer {
    /* 2 methods */

    public function testVariables_VariableNonascii01()  { $this->generic_test('Variables_VariableNonascii.01'); }
    public function testVariables_VariableNonascii02()  { $this->generic_test('Variables/VariableNonascii.02'); }
}
?>