<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class SameConditions extends Analyzer {
    /* 4 methods */

    public function testStructures_SameConditions01()  { $this->generic_test('Structures/SameConditions.01'); }
    public function testStructures_SameConditions02()  { $this->generic_test('Structures/SameConditions.02'); }
    public function testStructures_SameConditions03()  { $this->generic_test('Structures/SameConditions.03'); }
    public function testStructures_SameConditions04()  { $this->generic_test('Structures/SameConditions.04'); }
}
?>