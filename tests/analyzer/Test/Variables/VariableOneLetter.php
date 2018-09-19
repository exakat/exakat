<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class VariableOneLetter extends Analyzer {
    /* 2 methods */

    public function testVariables_VariableOneLetter01()  { $this->generic_test('Variables_VariableOneLetter.01'); }
    public function testVariables_VariableOneLetter02()  { $this->generic_test('Variables_VariableOneLetter.02'); }
}
?>