<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UnsetInForeach extends Analyzer {
    /* 5 methods */

    public function testStructures_UnsetInForeach01()  { $this->generic_test('Structures_UnsetInForeach.01'); }
    public function testStructures_UnsetInForeach02()  { $this->generic_test('Structures_UnsetInForeach.02'); }
    public function testStructures_UnsetInForeach03()  { $this->generic_test('Structures_UnsetInForeach.03'); }
    public function testStructures_UnsetInForeach04()  { $this->generic_test('Structures_UnsetInForeach.04'); }
    public function testStructures_UnsetInForeach05()  { $this->generic_test('Structures_UnsetInForeach.05'); }
}
?>