<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoSubstrOne extends Analyzer {
    /* 2 methods */

    public function testStructures_NoSubstrOne01()  { $this->generic_test('Structures_NoSubstrOne.01'); }
    public function testStructures_NoSubstrOne02()  { $this->generic_test('Structures/NoSubstrOne.02'); }
}
?>