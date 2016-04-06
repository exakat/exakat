<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_NoHardcodedIp extends Analyzer {
    /* 5 methods */

    public function testStructures_NoHardcodedIp01()  { $this->generic_test('Structures_NoHardcodedIp.01'); }
    public function testStructures_NoHardcodedIp02()  { $this->generic_test('Structures/NoHardcodedIp.02'); }
    public function testStructures_NoHardcodedIp03()  { $this->generic_test('Structures/NoHardcodedIp.03'); }
    public function testStructures_NoHardcodedIp04()  { $this->generic_test('Structures/NoHardcodedIp.04'); }
    public function testStructures_NoHardcodedIp05()  { $this->generic_test('Structures/NoHardcodedIp.05'); }
}
?>