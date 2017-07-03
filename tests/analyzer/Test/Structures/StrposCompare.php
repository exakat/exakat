<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_StrposCompare extends Analyzer {
    /* 8 methods */

    public function testStructures_StrposCompare01()  { $this->generic_test('Structures_StrposCompare.01'); }
    public function testStructures_StrposCompare02()  { $this->generic_test('Structures_StrposCompare.02'); }
    public function testStructures_StrposCompare03()  { $this->generic_test('Structures_StrposCompare.03'); }
    public function testStructures_StrposCompare04()  { $this->generic_test('Structures_StrposCompare.04'); }
    public function testStructures_StrposCompare05()  { $this->generic_test('Structures_StrposCompare.05'); }
    public function testStructures_StrposCompare06()  { $this->generic_test('Structures/StrposCompare.06'); }
    public function testStructures_StrposCompare07()  { $this->generic_test('Structures/StrposCompare.07'); }
    public function testStructures_StrposCompare08()  { $this->generic_test('Structures/StrposCompare.08'); }
}
?>