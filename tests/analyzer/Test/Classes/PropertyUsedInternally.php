<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class PropertyUsedInternally extends Analyzer {
    /* 9 methods */

    public function testClasses_PropertyUsedInternally01()  { $this->generic_test('Classes_PropertyUsedInternally.01'); }
    public function testClasses_PropertyUsedInternally02()  { $this->generic_test('Classes_PropertyUsedInternally.02'); }
    public function testClasses_PropertyUsedInternally03()  { $this->generic_test('Classes_PropertyUsedInternally.03'); }
    public function testClasses_PropertyUsedInternally04()  { $this->generic_test('Classes_PropertyUsedInternally.04'); }
    public function testClasses_PropertyUsedInternally05()  { $this->generic_test('Classes/PropertyUsedInternally.05'); }
    public function testClasses_PropertyUsedInternally06()  { $this->generic_test('Classes/PropertyUsedInternally.06'); }
    public function testClasses_PropertyUsedInternally07()  { $this->generic_test('Classes/PropertyUsedInternally.07'); }
    public function testClasses_PropertyUsedInternally08()  { $this->generic_test('Classes/PropertyUsedInternally.08'); }
    public function testClasses_PropertyUsedInternally09()  { $this->generic_test('Classes/PropertyUsedInternally.09'); }
}
?>