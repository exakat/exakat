<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_LocallyUnusedProperty extends Analyzer {
    /* 4 methods */

    public function testClasses_LocallyUnusedProperty01()  { $this->generic_test('Classes_LocallyUnusedProperty.01'); }
    public function testClasses_LocallyUnusedProperty02()  { $this->generic_test('Classes_LocallyUnusedProperty.02'); }
    public function testClasses_LocallyUnusedProperty03()  { $this->generic_test('Classes_LocallyUnusedProperty.03'); }
    public function testClasses_LocallyUnusedProperty04()  { $this->generic_test('Classes_LocallyUnusedProperty.04'); }
}
?>