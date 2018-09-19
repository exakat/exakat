<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class PossibleIncrement extends Analyzer {
    /* 2 methods */

    public function testStructures_PossibleIncrement01()  { $this->generic_test('Structures/PossibleIncrement.01'); }
    public function testStructures_PossibleIncrement02()  { $this->generic_test('Structures/PossibleIncrement.02'); }
}
?>