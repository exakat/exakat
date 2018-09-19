<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ConditionedFunctions extends Analyzer {
    /* 2 methods */

    public function testFunctions_ConditionedFunctions01()  { $this->generic_test('Functions_ConditionedFunctions.01'); }
    public function testFunctions_ConditionedFunctions02()  { $this->generic_test('Functions_ConditionedFunctions.02'); }
}
?>