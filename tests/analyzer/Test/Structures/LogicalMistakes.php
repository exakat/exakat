<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class LogicalMistakes extends Analyzer {
    /* 5 methods */

    public function testStructures_LogicalMistakes01()  { $this->generic_test('Structures/LogicalMistakes.01'); }
    public function testStructures_LogicalMistakes02()  { $this->generic_test('Structures/LogicalMistakes.02'); }
    public function testStructures_LogicalMistakes03()  { $this->generic_test('Structures/LogicalMistakes.03'); }
    public function testStructures_LogicalMistakes04()  { $this->generic_test('Structures/LogicalMistakes.04'); }
    public function testStructures_LogicalMistakes05()  { $this->generic_test('Structures/LogicalMistakes.05'); }
}
?>