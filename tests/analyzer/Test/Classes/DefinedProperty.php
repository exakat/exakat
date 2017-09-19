<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_DefinedProperty extends Analyzer {
    /* 5 methods */

    public function testClasses_DefinedProperty01()  { $this->generic_test('Classes_DefinedProperty.01'); }
    public function testClasses_DefinedProperty02()  { $this->generic_test('Classes_DefinedProperty.02'); }
    public function testClasses_DefinedProperty03()  { $this->generic_test('Classes_DefinedProperty.03'); }
    public function testClasses_DefinedProperty04()  { $this->generic_test('Classes_DefinedProperty.04'); }
    public function testClasses_DefinedProperty05()  { $this->generic_test('Classes/DefinedProperty.05'); }
}
?>