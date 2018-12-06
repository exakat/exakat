<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class AccessPrivate extends Analyzer {
    /* 5 methods */

    public function testClasses_AccessPrivate01()  { $this->generic_test('Classes_AccessPrivate.01'); }
    public function testClasses_AccessPrivate02()  { $this->generic_test('Classes_AccessPrivate.02'); }
    public function testClasses_AccessPrivate03()  { $this->generic_test('Classes_AccessPrivate.03'); }
    public function testClasses_AccessPrivate04()  { $this->generic_test('Classes/AccessPrivate.04'); }
    public function testClasses_AccessPrivate05()  { $this->generic_test('Classes/AccessPrivate.05'); }
}
?>