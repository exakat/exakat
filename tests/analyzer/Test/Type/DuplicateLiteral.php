<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DuplicateLiteral extends Analyzer {
    /* 7 methods */

    public function testType_DuplicateLiteral01()  { $this->generic_test('Type/DuplicateLiteral.01'); }
    public function testType_DuplicateLiteral02()  { $this->generic_test('Type/DuplicateLiteral.02'); }
    public function testType_DuplicateLiteral03()  { $this->generic_test('Type/DuplicateLiteral.03'); }
    public function testType_DuplicateLiteral04()  { $this->generic_test('Type/DuplicateLiteral.04'); }
    public function testType_DuplicateLiteral05()  { $this->generic_test('Type/DuplicateLiteral.05'); }
    public function testType_DuplicateLiteral06()  { $this->generic_test('Type/DuplicateLiteral.06'); }
    public function testType_DuplicateLiteral07()  { $this->generic_test('Type/DuplicateLiteral.07'); }
}
?>