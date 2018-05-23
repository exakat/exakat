<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_EchoWithConcat extends Analyzer {
    /* 5 methods */

    public function testStructures_EchoWithConcat01()  { $this->generic_test('Structures_EchoWithConcat.01'); }
    public function testStructures_EchoWithConcat02()  { $this->generic_test('Structures_EchoWithConcat.02'); }
    public function testStructures_EchoWithConcat03()  { $this->generic_test('Structures_EchoWithConcat.03'); }
    public function testStructures_EchoWithConcat04()  { $this->generic_test('Structures/EchoWithConcat.04'); }
    public function testStructures_EchoWithConcat05()  { $this->generic_test('Structures/EchoWithConcat.05'); }
}
?>