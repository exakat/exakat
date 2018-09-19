<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class RegexDelimiter extends Analyzer {
    /* 5 methods */

    public function testStructures_RegexDelimiter01()  { $this->generic_test('Structures/RegexDelimiter.01'); }
    public function testStructures_RegexDelimiter02()  { $this->generic_test('Structures/RegexDelimiter.02'); }
    public function testStructures_RegexDelimiter03()  { $this->generic_test('Structures/RegexDelimiter.03'); }
    public function testStructures_RegexDelimiter04()  { $this->generic_test('Structures/RegexDelimiter.04'); }
    public function testStructures_RegexDelimiter05()  { $this->generic_test('Structures/RegexDelimiter.05'); }
}
?>