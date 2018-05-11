<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_IsUpperFamily extends Analyzer {
    /* 11 methods */

    public function testClasses_IsUpperFamily01()  { $this->generic_test('Classes/IsUpperFamily.01'); }
    public function testClasses_IsUpperFamily02()  { $this->generic_test('Classes/IsUpperFamily.02'); }
    public function testClasses_IsUpperFamily03()  { $this->generic_test('Classes/IsUpperFamily.03'); }
    public function testClasses_IsUpperFamily04()  { $this->generic_test('Classes/IsUpperFamily.04'); }
    public function testClasses_IsUpperFamily05()  { $this->generic_test('Classes/IsUpperFamily.05'); }
    public function testClasses_IsUpperFamily06()  { $this->generic_test('Classes/IsUpperFamily.06'); }
    public function testClasses_IsUpperFamily07()  { $this->generic_test('Classes/IsUpperFamily.07'); }
    public function testClasses_IsUpperFamily08()  { $this->generic_test('Classes/IsUpperFamily.08'); }
    public function testClasses_IsUpperFamily09()  { $this->generic_test('Classes/IsUpperFamily.09'); }
    public function testClasses_IsUpperFamily10()  { $this->generic_test('Classes/IsUpperFamily.10'); }
    public function testClasses_IsUpperFamily11()  { $this->generic_test('Classes/IsUpperFamily.11'); }
}
?>