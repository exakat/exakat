<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class RepeatedPrint extends Analyzer {
    /* 3 methods */

    public function testStructures_RepeatedPrint01()  { $this->generic_test('Structures_RepeatedPrint.01'); }
    public function testStructures_RepeatedPrint02()  { $this->generic_test('Structures/RepeatedPrint.02'); }
    public function testStructures_RepeatedPrint03()  { $this->generic_test('Structures/RepeatedPrint.03'); }
}
?>