<?php

namespace Test\Files;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NotDefinitionsOnly extends Analyzer {
    /* 13 methods */

    public function testFiles_NotDefinitionsOnly01()  { $this->generic_test('Files_NotDefinitionsOnly.01'); }
    public function testFiles_NotDefinitionsOnly02()  { $this->generic_test('Files_NotDefinitionsOnly.02'); }
    public function testFiles_NotDefinitionsOnly03()  { $this->generic_test('Files_NotDefinitionsOnly.03'); }
    public function testFiles_NotDefinitionsOnly04()  { $this->generic_test('Files_NotDefinitionsOnly.04'); }
    public function testFiles_NotDefinitionsOnly05()  { $this->generic_test('Files_NotDefinitionsOnly.05'); }
    public function testFiles_NotDefinitionsOnly06()  { $this->generic_test('Files_NotDefinitionsOnly.06'); }
    public function testFiles_NotDefinitionsOnly07()  { $this->generic_test('Files/NotDefinitionsOnly.07'); }
    public function testFiles_NotDefinitionsOnly08()  { $this->generic_test('Files/NotDefinitionsOnly.08'); }
    public function testFiles_NotDefinitionsOnly09()  { $this->generic_test('Files/NotDefinitionsOnly.09'); }
    public function testFiles_NotDefinitionsOnly10()  { $this->generic_test('Files/NotDefinitionsOnly.10'); }
    public function testFiles_NotDefinitionsOnly11()  { $this->generic_test('Files/NotDefinitionsOnly.11'); }
    public function testFiles_NotDefinitionsOnly12()  { $this->generic_test('Files/NotDefinitionsOnly.12'); }
    public function testFiles_NotDefinitionsOnly13()  { $this->generic_test('Files/NotDefinitionsOnly.13'); }
}
?>