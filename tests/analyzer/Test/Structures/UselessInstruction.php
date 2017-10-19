<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_UselessInstruction extends Analyzer {
    /* 17 methods */

    public function testStructures_UselessInstruction01()  { $this->generic_test('Structures_UselessInstruction.01'); }
    public function testStructures_UselessInstruction02()  { $this->generic_test('Structures_UselessInstruction.02'); }
    public function testStructures_UselessInstruction03()  { $this->generic_test('Structures_UselessInstruction.03'); }
    public function testStructures_UselessInstruction04()  { $this->generic_test('Structures_UselessInstruction.04'); }
    public function testStructures_UselessInstruction05()  { $this->generic_test('Structures_UselessInstruction.05'); }
    public function testStructures_UselessInstruction06()  { $this->generic_test('Structures_UselessInstruction.06'); }
    public function testStructures_UselessInstruction07()  { $this->generic_test('Structures_UselessInstruction.07'); }
    public function testStructures_UselessInstruction08()  { $this->generic_test('Structures_UselessInstruction.08'); }
    public function testStructures_UselessInstruction09()  { $this->generic_test('Structures_UselessInstruction.09'); }
    public function testStructures_UselessInstruction10()  { $this->generic_test('Structures_UselessInstruction.10'); }
    public function testStructures_UselessInstruction11()  { $this->generic_test('Structures_UselessInstruction.11'); }
    public function testStructures_UselessInstruction12()  { $this->generic_test('Structures_UselessInstruction.12'); }
    public function testStructures_UselessInstruction13()  { $this->generic_test('Structures_UselessInstruction.13'); }
    public function testStructures_UselessInstruction14()  { $this->generic_test('Structures_UselessInstruction.14'); }
    public function testStructures_UselessInstruction15()  { $this->generic_test('Structures/UselessInstruction.15'); }
    public function testStructures_UselessInstruction16()  { $this->generic_test('Structures/UselessInstruction.16'); }
    public function testStructures_UselessInstruction17()  { $this->generic_test('Structures/UselessInstruction.17'); }
}
?>