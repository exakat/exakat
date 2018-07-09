<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_WrongCase extends Analyzer {
    /* 14 methods */

    public function testClasses_WrongCase01()  { $this->generic_test('Classes_WrongCase.01'); }
    public function testClasses_WrongCase02()  { $this->generic_test('Classes_WrongCase.02'); }
    public function testClasses_WrongCase03()  { $this->generic_test('Classes_WrongCase.03'); }
    public function testClasses_WrongCase04()  { $this->generic_test('Classes_WrongCase.04'); }
    public function testClasses_WrongCase05()  { $this->generic_test('Classes_WrongCase.05'); }
    public function testClasses_WrongCase06()  { $this->generic_test('Classes_WrongCase.06'); }
    public function testClasses_WrongCase07()  { $this->generic_test('Classes_WrongCase.07'); }
    public function testClasses_WrongCase08()  { $this->generic_test('Classes_WrongCase.08'); }
    public function testClasses_WrongCase09()  { $this->generic_test('Classes_WrongCase.09'); }
    public function testClasses_WrongCase10()  { $this->generic_test('Classes_WrongCase.10'); }
    public function testClasses_WrongCase11()  { $this->generic_test('Classes/WrongCase.11'); }
    public function testClasses_WrongCase12()  { $this->generic_test('Classes/WrongCase.12'); }
    public function testClasses_WrongCase13()  { $this->generic_test('Classes/WrongCase.13'); }
    public function testClasses_WrongCase14()  { $this->generic_test('Classes/WrongCase.14'); }
}
?>