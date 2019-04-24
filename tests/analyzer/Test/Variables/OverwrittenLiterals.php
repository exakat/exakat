<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class OverwrittenLiterals extends Analyzer {
    /* 6 methods */

    public function testVariables_OverwrittenLiterals01()  { $this->generic_test('Variables_OverwrittenLiterals.01'); }
    public function testVariables_OverwrittenLiterals02()  { $this->generic_test('Variables_OverwrittenLiterals.02'); }
    public function testVariables_OverwrittenLiterals03()  { $this->generic_test('Variables_OverwrittenLiterals.03'); }
    public function testVariables_OverwrittenLiterals04()  { $this->generic_test('Variables/OverwrittenLiterals.04'); }
    public function testVariables_OverwrittenLiterals05()  { $this->generic_test('Variables/OverwrittenLiterals.05'); }
    public function testVariables_OverwrittenLiterals06()  { $this->generic_test('Variables/OverwrittenLiterals.06'); }
}
?>