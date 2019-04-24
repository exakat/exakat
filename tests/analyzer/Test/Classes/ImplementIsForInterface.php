<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ImplementIsForInterface extends Analyzer {
    /* 4 methods */

    public function testClasses_ImplementIsForInterface01()  { $this->generic_test('Classes_ImplementIsForInterface.01'); }
    public function testClasses_ImplementIsForInterface02()  { $this->generic_test('Classes_ImplementIsForInterface.02'); }
    public function testClasses_ImplementIsForInterface03()  { $this->generic_test('Classes_ImplementIsForInterface.03'); }
    public function testClasses_ImplementIsForInterface04()  { $this->generic_test('Classes/ImplementIsForInterface.04'); }
}
?>