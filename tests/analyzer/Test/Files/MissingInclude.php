<?php

namespace Test\Files;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MissingInclude extends Analyzer {
    /* 6 methods */

    public function testFiles_MissingInclude01()  { $this->generic_test('Files/MissingInclude.01'); }
    public function testFiles_MissingInclude02()  { $this->generic_test('Files/MissingInclude.02'); }
    public function testFiles_MissingInclude03()  { $this->generic_test('Files/MissingInclude.03'); }
    public function testFiles_MissingInclude04()  { $this->generic_test('Files/MissingInclude.04'); }
    public function testFiles_MissingInclude05()  { $this->generic_test('Files/MissingInclude.05'); }
    public function testFiles_MissingInclude06()  { $this->generic_test('Files/MissingInclude.06'); }
}
?>