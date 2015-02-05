<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_TestClass extends Analyzer {
    /* 5 methods */

    public function testClasses_TestClass01()  { $this->generic_test('Classes_TestClass.01'); }
    public function testClasses_TestClass02()  { $this->generic_test('Classes_TestClass.02'); }
    public function testClasses_TestClass03()  { $this->generic_test('Classes_TestClass.03'); }
    public function testClasses_TestClass04()  { $this->generic_test('Classes_TestClass.04'); }
    public function testClasses_TestClass05()  { $this->generic_test('Classes_TestClass.05'); }
}
?>