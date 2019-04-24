<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBePrivate extends Analyzer {
    /* 7 methods */

    public function testClasses_CouldBePrivate01()  { $this->generic_test('Classes/CouldBePrivate.01'); }
    public function testClasses_CouldBePrivate02()  { $this->generic_test('Classes/CouldBePrivate.02'); }
    public function testClasses_CouldBePrivate03()  { $this->generic_test('Classes/CouldBePrivate.03'); }
    public function testClasses_CouldBePrivate04()  { $this->generic_test('Classes/CouldBePrivate.04'); }
    public function testClasses_CouldBePrivate05()  { $this->generic_test('Classes/CouldBePrivate.05'); }
    public function testClasses_CouldBePrivate06()  { $this->generic_test('Classes/CouldBePrivate.06'); }
    public function testClasses_CouldBePrivate07()  { $this->generic_test('Classes/CouldBePrivate.07'); }
}
?>