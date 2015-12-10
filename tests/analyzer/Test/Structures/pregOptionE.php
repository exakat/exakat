<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_pregOptionE extends Analyzer {
    /* 3 methods */

    public function testStructures_pregOptionE01()  { $this->generic_test('Structures_pregOptionE.01'); }
    public function testStructures_pregOptionE02()  { $this->generic_test('Structures/pregOptionE.02'); }
    public function testStructures_pregOptionE03()  { $this->generic_test('Structures/pregOptionE.03'); }
}
?>