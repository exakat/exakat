<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_InvalidRegex extends Analyzer {
    /* 4 methods */

    public function testStructures_InvalidRegex01()  { $this->generic_test('Structures/InvalidRegex.01'); }
    public function testStructures_InvalidRegex02()  { $this->generic_test('Structures/InvalidRegex.02'); }
    public function testStructures_InvalidRegex03()  { $this->generic_test('Structures/InvalidRegex.03'); }
    public function testStructures_InvalidRegex04()  { $this->generic_test('Structures/InvalidRegex.04'); }
}
?>