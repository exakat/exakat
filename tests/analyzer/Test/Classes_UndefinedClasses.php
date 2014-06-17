<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UndefinedClasses extends Analyzer {
    /* 6 methods */

    public function testClasses_UndefinedClasses01()  { $this->generic_test('Classes_UndefinedClasses.01'); }
    public function testClasses_UndefinedClasses02()  { $this->generic_test('Classes_UndefinedClasses.02'); }
    public function testClasses_UndefinedClasses03()  { $this->generic_test('Classes_UndefinedClasses.03'); }
    public function testClasses_UndefinedClasses04()  { $this->generic_test('Classes_UndefinedClasses.04'); }
    public function testClasses_UndefinedClasses05()  { $this->generic_test('Classes_UndefinedClasses.05'); }
    public function testClasses_UndefinedClasses06()  { $this->generic_test('Classes_UndefinedClasses.06'); }
}
?>