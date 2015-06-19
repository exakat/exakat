<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_ThisIsForClasses extends Analyzer {
    /* 5 methods */

    public function testClasses_ThisIsForClasses01()  { $this->generic_test('Classes_ThisIsForClasses.01'); }
    public function testClasses_ThisIsForClasses02()  { $this->generic_test('Classes_ThisIsForClasses.02'); }
    public function testClasses_ThisIsForClasses03()  { $this->generic_test('Classes_ThisIsForClasses.03'); }
    public function testClasses_ThisIsForClasses04()  { $this->generic_test('Classes_ThisIsForClasses.04'); }
    public function testClasses_ThisIsForClasses05()  { $this->generic_test('Classes_ThisIsForClasses.05'); }
}
?>