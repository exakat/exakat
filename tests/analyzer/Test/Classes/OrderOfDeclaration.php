<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class OrderOfDeclaration extends Analyzer {
    /* 5 methods */

    public function testClasses_OrderOfDeclaration01()  { $this->generic_test('Classes/OrderOfDeclaration.01'); }
    public function testClasses_OrderOfDeclaration02()  { $this->generic_test('Classes/OrderOfDeclaration.02'); }
    public function testClasses_OrderOfDeclaration03()  { $this->generic_test('Classes/OrderOfDeclaration.03'); }
    public function testClasses_OrderOfDeclaration04()  { $this->generic_test('Classes/OrderOfDeclaration.04'); }
    public function testClasses_OrderOfDeclaration05()  { $this->generic_test('Classes/OrderOfDeclaration.05'); }
}
?>