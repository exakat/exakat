<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class VariableLong extends Analyzer {
    /* 2 methods */

    public function testVariables_VariableLong01()  { $this->generic_test('Variables_VariableLong.01'); }
    public function testVariables_VariableLong02()  { $this->generic_test('Variables/VariableLong.02'); }
}
?>