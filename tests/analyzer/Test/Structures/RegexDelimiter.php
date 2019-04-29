<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RegexDelimiter extends Analyzer {
    /* 6 methods */

    public function testStructures_RegexDelimiter01()  { $this->generic_test('Structures/RegexDelimiter.01'); }
    public function testStructures_RegexDelimiter02()  { $this->generic_test('Structures/RegexDelimiter.02'); }
    public function testStructures_RegexDelimiter03()  { $this->generic_test('Structures/RegexDelimiter.03'); }
    public function testStructures_RegexDelimiter04()  { $this->generic_test('Structures/RegexDelimiter.04'); }
    public function testStructures_RegexDelimiter05()  { $this->generic_test('Structures/RegexDelimiter.05'); }
    public function testStructures_RegexDelimiter06()  { $this->generic_test('Structures/RegexDelimiter.06'); }
}
?>