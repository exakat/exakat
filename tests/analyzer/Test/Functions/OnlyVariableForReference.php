<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class OnlyVariableForReference extends Analyzer {
    /* 3 methods */

    public function testFunctions_OnlyVariableForReference01()  { $this->generic_test('Functions/OnlyVariableForReference.01'); }
    public function testFunctions_OnlyVariableForReference02()  { $this->generic_test('Functions/OnlyVariableForReference.02'); }
    public function testFunctions_OnlyVariableForReference03()  { $this->generic_test('Functions/OnlyVariableForReference.03'); }
}
?>