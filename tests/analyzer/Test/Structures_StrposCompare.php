<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_StrposCompare extends Analyzer {
    /* 4 methods */

    public function testStructures_StrposCompare01()  { $this->generic_test('Structures_StrposCompare.01'); }
    public function testStructures_StrposCompare02()  { $this->generic_test('Structures_StrposCompare.02'); }
    public function testStructures_StrposCompare03()  { $this->generic_test('Structures_StrposCompare.03'); }
    public function testStructures_StrposCompare04()  { $this->generic_test('Structures_StrposCompare.04'); }
}
?>