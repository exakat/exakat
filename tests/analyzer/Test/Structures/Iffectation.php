<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Iffectation extends Analyzer {
    /* 3 methods */

    public function testStructures_Iffectation01()  { $this->generic_test('Structures_Iffectation.01'); }
    public function testStructures_Iffectation02()  { $this->generic_test('Structures_Iffectation.02'); }
    public function testStructures_Iffectation03()  { $this->generic_test('Structures_Iffectation.03'); }
}
?>