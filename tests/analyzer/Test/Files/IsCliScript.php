<?php

namespace Test\Files;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsCliScript extends Analyzer {
    /* 4 methods */

    public function testFiles_IsCliScript01()  { $this->generic_test('Files/IsCliScript.01'); }
    public function testFiles_IsCliScript02()  { $this->generic_test('Files/IsCliScript.02'); }
    public function testFiles_IsCliScript03()  { $this->generic_test('Files/IsCliScript.03'); }
    public function testFiles_IsCliScript04()  { $this->generic_test('Files/IsCliScript.04'); }
}
?>