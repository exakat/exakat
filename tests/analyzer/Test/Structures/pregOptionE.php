<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_pregOptionE extends Analyzer {
    /* 7 methods */

    public function testStructures_pregOptionE01()  { $this->generic_test('Structures_pregOptionE.01'); }
    public function testStructures_pregOptionE02()  { $this->generic_test('Structures/pregOptionE.02'); }
    public function testStructures_pregOptionE03()  { $this->generic_test('Structures/pregOptionE.03'); }
    public function testStructures_pregOptionE04()  { $this->generic_test('Structures/pregOptionE.04'); }
    public function testStructures_pregOptionE05()  { $this->generic_test('Structures/pregOptionE.05'); }
    public function testStructures_pregOptionE06()  { $this->generic_test('Structures/pregOptionE.06'); }
    public function testStructures_pregOptionE07()  { $this->generic_test('Structures/pregOptionE.07'); }
}
?>