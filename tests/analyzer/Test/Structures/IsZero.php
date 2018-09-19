<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsZero extends Analyzer {
    /* 6 methods */

    public function testStructures_IsZero01()  { $this->generic_test('Structures/IsZero.01'); }
    public function testStructures_IsZero02()  { $this->generic_test('Structures/IsZero.02'); }
    public function testStructures_IsZero03()  { $this->generic_test('Structures/IsZero.03'); }
    public function testStructures_IsZero04()  { $this->generic_test('Structures/IsZero.04'); }
    public function testStructures_IsZero05()  { $this->generic_test('Structures/IsZero.05'); }
    public function testStructures_IsZero06()  { $this->generic_test('Structures/IsZero.06'); }
}
?>