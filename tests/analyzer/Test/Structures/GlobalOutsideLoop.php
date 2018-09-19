<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class GlobalOutsideLoop extends Analyzer {
    /* 1 methods */

    public function testStructures_GlobalOutsideLoop01()  { $this->generic_test('Structures_GlobalOutsideLoop.01'); }
}
?>