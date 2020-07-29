<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontCompareTypedBoolean extends Analyzer {
    /* 3 methods */

    public function testStructures_DontCompareTypedBoolean01()  { $this->generic_test('Structures/DontCompareTypedBoolean.01'); }
    public function testStructures_DontCompareTypedBoolean02()  { $this->generic_test('Structures/DontCompareTypedBoolean.02'); }
    public function testStructures_DontCompareTypedBoolean03()  { $this->generic_test('Structures/DontCompareTypedBoolean.03'); }
}
?>