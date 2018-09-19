<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ObjectReferences extends Analyzer {
    /* 7 methods */

    public function testStructures_ObjectReferences01()  { $this->generic_test('Structures_ObjectReferences.01'); }
    public function testStructures_ObjectReferences02()  { $this->generic_test('Structures_ObjectReferences.02'); }
    public function testStructures_ObjectReferences03()  { $this->generic_test('Structures_ObjectReferences.03'); }
    public function testStructures_ObjectReferences04()  { $this->generic_test('Structures/ObjectReferences.04'); }
    public function testStructures_ObjectReferences05()  { $this->generic_test('Structures/ObjectReferences.05'); }
    public function testStructures_ObjectReferences06()  { $this->generic_test('Structures/ObjectReferences.06'); }
    public function testStructures_ObjectReferences07()  { $this->generic_test('Structures/ObjectReferences.07'); }
}
?>