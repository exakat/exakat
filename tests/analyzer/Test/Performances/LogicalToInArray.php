<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class LogicalToInArray extends Analyzer {
    /* 3 methods */

    public function testPerformances_LogicalToInArray01()  { $this->generic_test('Performances/LogicalToInArray.01'); }
    public function testPerformances_LogicalToInArray02()  { $this->generic_test('Performances/LogicalToInArray.02'); }
    public function testPerformances_LogicalToInArray03()  { $this->generic_test('Performances/LogicalToInArray.03'); }
}
?>