<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_IsModified extends Analyzer {
    /* 7 methods */

    public function testClasses_IsModified01()  { $this->generic_test('Classes_IsModified.01'); }
    public function testClasses_IsModified02()  { $this->generic_test('Classes_IsModified.02'); }
    public function testClasses_IsModified03()  { $this->generic_test('Classes_IsModified.03'); }
    public function testClasses_IsModified04()  { $this->generic_test('Classes_IsModified.04'); }
    public function testClasses_IsModified05()  { $this->generic_test('Classes_IsModified.05'); }
    public function testClasses_IsModified06()  { $this->generic_test('Classes/IsModified.06'); }
    public function testClasses_IsModified07()  { $this->generic_test('Classes/IsModified.07'); }
}
?>