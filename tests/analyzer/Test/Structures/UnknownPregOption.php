<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_UnknownPregOption extends Analyzer {
    /* 6 methods */

    public function testStructures_UnknownPregOption01()  { $this->generic_test('Structures/UnknownPregOption.01'); }
    public function testStructures_UnknownPregOption02()  { $this->generic_test('Structures/UnknownPregOption.02'); }
    public function testStructures_UnknownPregOption03()  { $this->generic_test('Structures/UnknownPregOption.03'); }
    public function testStructures_UnknownPregOption04()  { $this->generic_test('Structures/UnknownPregOption.04'); }
    public function testStructures_UnknownPregOption05()  { $this->generic_test('Structures/UnknownPregOption.05'); }
    public function testStructures_UnknownPregOption06()  { $this->generic_test('Structures/UnknownPregOption.06'); }
}
?>