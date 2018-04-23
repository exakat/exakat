<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_CouldBeStatic extends Analyzer {
    /* 4 methods */

    public function testStructures_CouldBeStatic01()  { $this->generic_test('Structures_CouldBeStatic.01'); }
    public function testStructures_CouldBeStatic02()  { $this->generic_test('Structures/CouldBeStatic.02'); }
    public function testStructures_CouldBeStatic03()  { $this->generic_test('Structures/CouldBeStatic.03'); }
    public function testStructures_CouldBeStatic04()  { $this->generic_test('Structures/CouldBeStatic.04'); }
}
?>