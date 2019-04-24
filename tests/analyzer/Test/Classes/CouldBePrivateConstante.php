<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBePrivateConstante extends Analyzer {
    /* 3 methods */

    public function testClasses_CouldBePrivateConstante01()  { $this->generic_test('Classes/CouldBePrivateConstante.01'); }
    public function testClasses_CouldBePrivateConstante02()  { $this->generic_test('Classes/CouldBePrivateConstante.02'); }
    public function testClasses_CouldBePrivateConstante03()  { $this->generic_test('Classes/CouldBePrivateConstante.03'); }
}
?>