<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessBrackets extends Analyzer {
    /* 6 methods */

    public function testStructures_UselessBrackets01()  { $this->generic_test('Structures_UselessBrackets.01'); }
    public function testStructures_UselessBrackets02()  { $this->generic_test('Structures_UselessBrackets.02'); }
    public function testStructures_UselessBrackets03()  { $this->generic_test('Structures_UselessBrackets.03'); }
    public function testStructures_UselessBrackets04()  { $this->generic_test('Structures_UselessBrackets.04'); }
    public function testStructures_UselessBrackets05()  { $this->generic_test('Structures_UselessBrackets.05'); }
    public function testStructures_UselessBrackets06()  { $this->generic_test('Structures/UselessBrackets.06'); }
}
?>