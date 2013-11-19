<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Structures_StrposCompare extends Analyzer {
    /* 2 methods */

    public function testStructures_StrposCompare01()  { $this->generic_test('Structures_StrposCompare.01'); }
    public function testStructures_StrposCompare02()  { $this->generic_test('Structures_StrposCompare.02'); }
}
?>