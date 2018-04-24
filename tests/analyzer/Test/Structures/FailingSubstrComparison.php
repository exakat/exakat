<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_FailingSubstrComparison extends Analyzer {
    /* 7 methods */

    public function testStructures_FailingSubstrComparison01()  { $this->generic_test('Structures/FailingSubstrComparison.01'); }
    public function testStructures_FailingSubstrComparison02()  { $this->generic_test('Structures/FailingSubstrComparison.02'); }
    public function testStructures_FailingSubstrComparison03()  { $this->generic_test('Structures/FailingSubstrComparison.03'); }
    public function testStructures_FailingSubstrComparison04()  { $this->generic_test('Structures/FailingSubstrComparison.04'); }
    public function testStructures_FailingSubstrComparison05()  { $this->generic_test('Structures/FailingSubstrComparison.05'); }
    public function testStructures_FailingSubstrComparison06()  { $this->generic_test('Structures/FailingSubstrComparison.06'); }
    public function testStructures_FailingSubstrComparison07()  { $this->generic_test('Structures/FailingSubstrComparison.07'); }
}
?>