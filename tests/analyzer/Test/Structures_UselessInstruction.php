<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_UselessInstruction extends Analyzer {
    /* 5 methods */

    public function testStructures_UselessInstruction01()  { $this->generic_test('Structures_UselessInstruction.01'); }
    public function testStructures_UselessInstruction02()  { $this->generic_test('Structures_UselessInstruction.02'); }
    public function testStructures_UselessInstruction03()  { $this->generic_test('Structures_UselessInstruction.03'); }
    public function testStructures_UselessInstruction04()  { $this->generic_test('Structures_UselessInstruction.04'); }
    public function testStructures_UselessInstruction05()  { $this->generic_test('Structures_UselessInstruction.05'); }
}
?>