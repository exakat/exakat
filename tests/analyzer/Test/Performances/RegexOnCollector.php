<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class RegexOnCollector extends Analyzer {
    /* 1 methods */

    public function testPerformances_RegexOnCollector01()  { $this->generic_test('Performances/RegexOnCollector.01'); }
}
?>