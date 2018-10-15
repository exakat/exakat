<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoEmptyRegex extends Analyzer {
    /* 3 methods */

    public function testStructures_NoEmptyRegex01()  { $this->generic_test('Structures/NoEmptyRegex.01'); }
    public function testStructures_NoEmptyRegex02()  { $this->generic_test('Structures/NoEmptyRegex.02'); }
    public function testStructures_NoEmptyRegex03()  { $this->generic_test('Structures/NoEmptyRegex.03'); }
}
?>