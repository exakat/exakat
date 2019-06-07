<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PropertyNeverUsed extends Analyzer {
    /* 11 methods */

    public function testClasses_PropertyNeverUsed01()  { $this->generic_test('Classes_PropertyNeverUsed.01'); }
    public function testClasses_PropertyNeverUsed02()  { $this->generic_test('Classes_PropertyNeverUsed.02'); }
    public function testClasses_PropertyNeverUsed03()  { $this->generic_test('Classes_PropertyNeverUsed.03'); }
    public function testClasses_PropertyNeverUsed04()  { $this->generic_test('Classes_PropertyNeverUsed.04'); }
    public function testClasses_PropertyNeverUsed05()  { $this->generic_test('Classes_PropertyNeverUsed.05'); }
    public function testClasses_PropertyNeverUsed06()  { $this->generic_test('Classes_PropertyNeverUsed.06'); }
    public function testClasses_PropertyNeverUsed07()  { $this->generic_test('Classes_PropertyNeverUsed.07'); }
    public function testClasses_PropertyNeverUsed08()  { $this->generic_test('Classes_PropertyNeverUsed.08'); }
    public function testClasses_PropertyNeverUsed09()  { $this->generic_test('Classes_PropertyNeverUsed.09'); }
    public function testClasses_PropertyNeverUsed10()  { $this->generic_test('Classes/PropertyNeverUsed.10'); }
    public function testClasses_PropertyNeverUsed11()  { $this->generic_test('Classes/PropertyNeverUsed.11'); }
}
?>