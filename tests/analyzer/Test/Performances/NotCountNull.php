<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NotCountNull extends Analyzer {
    /* 2 methods */

    public function testPerformances_NotCountNull01()  { $this->generic_test('Performances/NotCountNull.01'); }
    public function testPerformances_NotCountNull02()  { $this->generic_test('Performances/NotCountNull.02'); }
}
?>