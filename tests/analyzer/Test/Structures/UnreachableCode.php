<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_UnreachableCode extends Analyzer {
    /* 9 methods */

    public function testStructures_UnreachableCode01()  { $this->generic_test('Structures_UnreachableCode.01'); }
    public function testStructures_UnreachableCode02()  { $this->generic_test('Structures_UnreachableCode.02'); }
    public function testStructures_UnreachableCode03()  { $this->generic_test('Structures_UnreachableCode.03'); }
    public function testStructures_UnreachableCode04()  { $this->generic_test('Structures_UnreachableCode.04'); }
    public function testStructures_UnreachableCode05()  { $this->generic_test('Structures/UnreachableCode.05'); }
    public function testStructures_UnreachableCode06()  { $this->generic_test('Structures/UnreachableCode.06'); }
    public function testStructures_UnreachableCode07()  { $this->generic_test('Structures/UnreachableCode.07'); }
    public function testStructures_UnreachableCode08()  { $this->generic_test('Structures/UnreachableCode.08'); }
    public function testStructures_UnreachableCode09()  { $this->generic_test('Structures/UnreachableCode.09'); }
}
?>