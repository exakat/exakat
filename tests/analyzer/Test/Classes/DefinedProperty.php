<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class DefinedProperty extends Analyzer {
    /* 5 methods */

    public function testClasses_DefinedProperty01()  { $this->generic_test('Classes_DefinedProperty.01'); }
    public function testClasses_DefinedProperty02()  { $this->generic_test('Classes_DefinedProperty.02'); }
    public function testClasses_DefinedProperty03()  { $this->generic_test('Classes_DefinedProperty.03'); }
    public function testClasses_DefinedProperty04()  { $this->generic_test('Classes_DefinedProperty.04'); }
    public function testClasses_DefinedProperty05()  { $this->generic_test('Classes/DefinedProperty.05'); }
}
?>