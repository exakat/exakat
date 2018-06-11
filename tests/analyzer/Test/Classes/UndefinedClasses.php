<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UndefinedClasses extends Analyzer {
    /* 16 methods */

    public function testClasses_UndefinedClasses01()  { $this->generic_test('Classes_UndefinedClasses.01'); }
    public function testClasses_UndefinedClasses02()  { $this->generic_test('Classes_UndefinedClasses.02'); }
    public function testClasses_UndefinedClasses03()  { $this->generic_test('Classes_UndefinedClasses.03'); }
    public function testClasses_UndefinedClasses04()  { $this->generic_test('Classes_UndefinedClasses.04'); }
    public function testClasses_UndefinedClasses05()  { $this->generic_test('Classes_UndefinedClasses.05'); }
    public function testClasses_UndefinedClasses06()  { $this->generic_test('Classes_UndefinedClasses.06'); }
    public function testClasses_UndefinedClasses07()  { $this->generic_test('Classes_UndefinedClasses.07'); }
    public function testClasses_UndefinedClasses08()  { $this->generic_test('Classes_UndefinedClasses.08'); }
    public function testClasses_UndefinedClasses09()  { $this->generic_test('Classes_UndefinedClasses.09'); }
    public function testClasses_UndefinedClasses10()  { $this->generic_test('Classes_UndefinedClasses.10'); }
    public function testClasses_UndefinedClasses11()  { $this->generic_test('Classes/UndefinedClasses.11'); }
    public function testClasses_UndefinedClasses12()  { $this->generic_test('Classes/UndefinedClasses.12'); }
    public function testClasses_UndefinedClasses13()  { $this->generic_test('Classes/UndefinedClasses.13'); }
    public function testClasses_UndefinedClasses14()  { $this->generic_test('Classes/UndefinedClasses.14'); }
    public function testClasses_UndefinedClasses15()  { $this->generic_test('Classes/UndefinedClasses.15'); }
    public function testClasses_UndefinedClasses16()  { $this->generic_test('Classes/UndefinedClasses.16'); }
}
?>