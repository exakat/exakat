<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_DoubleAssignation extends Analyzer {
    /* 6 methods */

    public function testStructures_DoubleAssignation01()  { $this->generic_test('Structures_DoubleAssignation.01'); }
    public function testStructures_DoubleAssignation02()  { $this->generic_test('Structures_DoubleAssignation.02'); }
    public function testStructures_DoubleAssignation03()  { $this->generic_test('Structures_DoubleAssignation.03'); }
    public function testStructures_DoubleAssignation04()  { $this->generic_test('Structures/DoubleAssignation.04'); }
    public function testStructures_DoubleAssignation05()  { $this->generic_test('Structures/DoubleAssignation.05'); }
    public function testStructures_DoubleAssignation06()  { $this->generic_test('Structures/DoubleAssignation.06'); }
}
?>