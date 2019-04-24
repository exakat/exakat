<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessParenthesis extends Analyzer {
    /* 10 methods */

    public function testStructures_UselessParenthesis01()  { $this->generic_test('Structures_UselessParenthesis.01'); }
    public function testStructures_UselessParenthesis02()  { $this->generic_test('Structures_UselessParenthesis.02'); }
    public function testStructures_UselessParenthesis03()  { $this->generic_test('Structures_UselessParenthesis.03'); }
    public function testStructures_UselessParenthesis04()  { $this->generic_test('Structures_UselessParenthesis.04'); }
    public function testStructures_UselessParenthesis05()  { $this->generic_test('Structures/UselessParenthesis.05'); }
    public function testStructures_UselessParenthesis06()  { $this->generic_test('Structures/UselessParenthesis.06'); }
    public function testStructures_UselessParenthesis07()  { $this->generic_test('Structures/UselessParenthesis.07'); }
    public function testStructures_UselessParenthesis08()  { $this->generic_test('Structures/UselessParenthesis.08'); }
    public function testStructures_UselessParenthesis09()  { $this->generic_test('Structures/UselessParenthesis.09'); }
    public function testStructures_UselessParenthesis10()  { $this->generic_test('Structures/UselessParenthesis.10'); }
}
?>