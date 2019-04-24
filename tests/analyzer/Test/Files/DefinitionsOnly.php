<?php

namespace Test\Files;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DefinitionsOnly extends Analyzer {
    /* 10 methods */

    public function testFiles_DefinitionsOnly01()  { $this->generic_test('Files_DefinitionsOnly.01'); }
    public function testFiles_DefinitionsOnly02()  { $this->generic_test('Files_DefinitionsOnly.02'); }
    public function testFiles_DefinitionsOnly03()  { $this->generic_test('Files_DefinitionsOnly.03'); }
    public function testFiles_DefinitionsOnly04()  { $this->generic_test('Files_DefinitionsOnly.04'); }
    public function testFiles_DefinitionsOnly05()  { $this->generic_test('Files_DefinitionsOnly.05'); }
    public function testFiles_DefinitionsOnly06()  { $this->generic_test('Files_DefinitionsOnly.06'); }
    public function testFiles_DefinitionsOnly07()  { $this->generic_test('Files_DefinitionsOnly.07'); }
    public function testFiles_DefinitionsOnly08()  { $this->generic_test('Files_DefinitionsOnly.08'); }
    public function testFiles_DefinitionsOnly09()  { $this->generic_test('Files/DefinitionsOnly.09'); }
    public function testFiles_DefinitionsOnly10()  { $this->generic_test('Files/DefinitionsOnly.10'); }
}
?>