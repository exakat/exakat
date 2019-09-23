<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PropertyCouldBeLocal extends Analyzer {
    /* 7 methods */

    public function testClasses_PropertyCouldBeLocal01()  { $this->generic_test('Classes/PropertyCouldBeLocal.01'); }
    public function testClasses_PropertyCouldBeLocal02()  { $this->generic_test('Classes/PropertyCouldBeLocal.02'); }
    public function testClasses_PropertyCouldBeLocal03()  { $this->generic_test('Classes/PropertyCouldBeLocal.03'); }
    public function testClasses_PropertyCouldBeLocal04()  { $this->generic_test('Classes/PropertyCouldBeLocal.04'); }
    public function testClasses_PropertyCouldBeLocal05()  { $this->generic_test('Classes/PropertyCouldBeLocal.05'); }
    public function testClasses_PropertyCouldBeLocal06()  { $this->generic_test('Classes/PropertyCouldBeLocal.06'); }
    public function testClasses_PropertyCouldBeLocal07()  { $this->generic_test('Classes/PropertyCouldBeLocal.07'); }
}
?>