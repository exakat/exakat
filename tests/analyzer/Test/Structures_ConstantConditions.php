<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ConstantConditions extends Analyzer {
    /* 6 methods */

    public function testStructures_ConstantConditions01()  { $this->generic_test('Structures_ConstantConditions.01'); }
    public function testStructures_ConstantConditions02()  { $this->generic_test('Structures_ConstantConditions.02'); }
    public function testStructures_ConstantConditions03()  { $this->generic_test('Structures_ConstantConditions.03'); }
    public function testStructures_ConstantConditions04()  { $this->generic_test('Structures_ConstantConditions.04'); }
    public function testStructures_ConstantConditions05()  { $this->generic_test('Structures_ConstantConditions.05'); }
    public function testStructures_ConstantConditions06()  { $this->generic_test('Structures_ConstantConditions.06'); }
}
?>