<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_UnusedGlobal extends Analyzer {
    /* 3 methods */

    public function testStructures_UnusedGlobal01()  { $this->generic_test('Structures_UnusedGlobal.01'); }
    public function testStructures_UnusedGlobal02()  { $this->generic_test('Structures_UnusedGlobal.02'); }
    public function testStructures_UnusedGlobal03()  { $this->generic_test('Structures_UnusedGlobal.03'); }
}
?>