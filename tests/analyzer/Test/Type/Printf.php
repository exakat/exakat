<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Printf extends Analyzer {
    /* 2 methods */

    public function testType_Printf01()  { $this->generic_test('Type/Printf.01'); }
    public function testType_Printf02()  { $this->generic_test('Type/Printf.02'); }
}
?>