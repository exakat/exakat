<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_BailOutEarly extends Analyzer {
    /* 5 methods */

    public function testStructures_BailOutEarly01()  { $this->generic_test('Structures/BailOutEarly.01'); }
    public function testStructures_BailOutEarly02()  { $this->generic_test('Structures/BailOutEarly.02'); }
    public function testStructures_BailOutEarly03()  { $this->generic_test('Structures/BailOutEarly.03'); }
    public function testStructures_BailOutEarly04()  { $this->generic_test('Structures/BailOutEarly.04'); }
    public function testStructures_BailOutEarly05()  { $this->generic_test('Structures/BailOutEarly.05'); }
}
?>