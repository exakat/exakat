<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Regex extends Analyzer {
    /* 5 methods */

    public function testType_Regex01()  { $this->generic_test('Type/Regex.01'); }
    public function testType_Regex02()  { $this->generic_test('Type/Regex.02'); }
    public function testType_Regex03()  { $this->generic_test('Type/Regex.03'); }
    public function testType_Regex04()  { $this->generic_test('Type/Regex.04'); }
    public function testType_Regex05()  { $this->generic_test('Type/Regex.05'); }
}
?>