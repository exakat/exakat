<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class FetchOneRowFormat extends Analyzer {
    /* 1 methods */

    public function testPerformances_FetchOneRowFormat01()  { $this->generic_test('Performances/FetchOneRowFormat.01'); }
}
?>