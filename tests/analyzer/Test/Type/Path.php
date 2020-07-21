<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Path extends Analyzer {
    /* 5 methods */

    public function testType_Path01()  { $this->generic_test('Type/Path.01'); }
    public function testType_Path02()  { $this->generic_test('Type/Path.02'); }
    public function testType_Path03()  { $this->generic_test('Type/Path.03'); }
    public function testType_Path04()  { $this->generic_test('Type/Path.04'); }
    public function testType_Path05()  { $this->generic_test('Type/Path.05'); }
}
?>