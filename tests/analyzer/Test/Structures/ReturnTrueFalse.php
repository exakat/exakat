<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ReturnTrueFalse extends Analyzer {
    /* 3 methods */

    public function testStructures_ReturnTrueFalse01()  { $this->generic_test('Structures/ReturnTrueFalse.01'); }
    public function testStructures_ReturnTrueFalse02()  { $this->generic_test('Structures/ReturnTrueFalse.02'); }
    public function testStructures_ReturnTrueFalse03()  { $this->generic_test('Structures/ReturnTrueFalse.03'); }
}
?>