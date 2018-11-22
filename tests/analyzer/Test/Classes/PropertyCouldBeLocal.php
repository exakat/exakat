<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PropertyCouldBeLocal extends Analyzer {
    /* 5 methods */

    public function testClasses_PropertyCouldBeLocal01()  { $this->generic_test('Classes/PropertyCouldBeLocal.01'); }
    public function testClasses_PropertyCouldBeLocal02()  { $this->generic_test('Classes/PropertyCouldBeLocal.02'); }
    public function testClasses_PropertyCouldBeLocal03()  { $this->generic_test('Classes/PropertyCouldBeLocal.03'); }
    public function testClasses_PropertyCouldBeLocal04()  { $this->generic_test('Classes/PropertyCouldBeLocal.04'); }
    public function testClasses_PropertyCouldBeLocal05()  { $this->generic_test('Classes/PropertyCouldBeLocal.05'); }
}
?>