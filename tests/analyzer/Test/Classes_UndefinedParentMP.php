<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UndefinedParentMP extends Analyzer {
    /* 10 methods */

    public function testClasses_UndefinedParentMP01()  { $this->generic_test('Classes_UndefinedParentMP.01'); }
    public function testClasses_UndefinedParentMP02()  { $this->generic_test('Classes_UndefinedParentMP.02'); }
    public function testClasses_UndefinedParentMP03()  { $this->generic_test('Classes_UndefinedParentMP.03'); }
    public function testClasses_UndefinedParentMP04()  { $this->generic_test('Classes_UndefinedParentMP.04'); }
    public function testClasses_UndefinedParentMP05()  { $this->generic_test('Classes_UndefinedParentMP.05'); }
    public function testClasses_UndefinedParentMP06()  { $this->generic_test('Classes_UndefinedParentMP.06'); }
    public function testClasses_UndefinedParentMP07()  { $this->generic_test('Classes_UndefinedParentMP.07'); }
    public function testClasses_UndefinedParentMP08()  { $this->generic_test('Classes_UndefinedParentMP.08'); }
    public function testClasses_UndefinedParentMP09()  { $this->generic_test('Classes_UndefinedParentMP.09'); }
    public function testClasses_UndefinedParentMP10()  { $this->generic_test('Classes_UndefinedParentMP.10'); }
}
?>