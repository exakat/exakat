<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ShouldPreprocess extends Analyzer {
    /* 6 methods */

    public function testStructures_ShouldPreprocess01()  { $this->generic_test('Structures_ShouldPreprocess.01'); }
    public function testStructures_ShouldPreprocess02()  { $this->generic_test('Structures_ShouldPreprocess.02'); }
    public function testStructures_ShouldPreprocess03()  { $this->generic_test('Structures/ShouldPreprocess.03'); }
    public function testStructures_ShouldPreprocess04()  { $this->generic_test('Structures/ShouldPreprocess.04'); }
    public function testStructures_ShouldPreprocess05()  { $this->generic_test('Structures/ShouldPreprocess.05'); }
    public function testStructures_ShouldPreprocess06()  { $this->generic_test('Structures/ShouldPreprocess.06'); }
}
?>