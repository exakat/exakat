<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class LocallyUsedProperty extends Analyzer {
    /* 6 methods */

    public function testClasses_LocallyUsedProperty01()  { $this->generic_test('Classes_LocallyUsedProperty.01'); }
    public function testClasses_LocallyUsedProperty02()  { $this->generic_test('Classes_LocallyUsedProperty.02'); }
    public function testClasses_LocallyUsedProperty03()  { $this->generic_test('Classes_LocallyUsedProperty.03'); }
    public function testClasses_LocallyUsedProperty04()  { $this->generic_test('Classes/LocallyUsedProperty.04'); }
    public function testClasses_LocallyUsedProperty05()  { $this->generic_test('Classes/LocallyUsedProperty.05'); }
    public function testClasses_LocallyUsedProperty06()  { $this->generic_test('Classes/LocallyUsedProperty.06'); }
}
?>