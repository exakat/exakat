<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ReuseVariable extends Analyzer {
    /* 6 methods */

    public function testStructures_ReuseVariable01()  { $this->generic_test('Structures/ReuseVariable.01'); }
    public function testStructures_ReuseVariable02()  { $this->generic_test('Structures/ReuseVariable.02'); }
    public function testStructures_ReuseVariable03()  { $this->generic_test('Structures/ReuseVariable.03'); }
    public function testStructures_ReuseVariable04()  { $this->generic_test('Structures/ReuseVariable.04'); }
    public function testStructures_ReuseVariable05()  { $this->generic_test('Structures/ReuseVariable.05'); }
    public function testStructures_ReuseVariable06()  { $this->generic_test('Structures/ReuseVariable.06'); }
}
?>