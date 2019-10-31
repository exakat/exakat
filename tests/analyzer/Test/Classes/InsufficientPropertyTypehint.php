<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class InsufficientPropertyTypehint extends Analyzer {
    /* 6 methods */

    public function testClasses_InsufficientPropertyTypehint01()  { $this->generic_test('Classes/InsufficientPropertyTypehint.01'); }
    public function testClasses_InsufficientPropertyTypehint02()  { $this->generic_test('Classes/InsufficientPropertyTypehint.02'); }
    public function testClasses_InsufficientPropertyTypehint03()  { $this->generic_test('Classes/InsufficientPropertyTypehint.03'); }
    public function testClasses_InsufficientPropertyTypehint04()  { $this->generic_test('Classes/InsufficientPropertyTypehint.04'); }
    public function testClasses_InsufficientPropertyTypehint05()  { $this->generic_test('Classes/InsufficientPropertyTypehint.05'); }
    public function testClasses_InsufficientPropertyTypehint06()  { $this->generic_test('Classes/InsufficientPropertyTypehint.06'); }
}
?>