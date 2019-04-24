<?php

namespace Test\Files;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsComponent extends Analyzer {
    /* 7 methods */

    public function testFiles_IsComponent01()  { $this->generic_test('Files/IsComponent.01'); }
    public function testFiles_IsComponent02()  { $this->generic_test('Files/IsComponent.02'); }
    public function testFiles_IsComponent03()  { $this->generic_test('Files/IsComponent.03'); }
    public function testFiles_IsComponent04()  { $this->generic_test('Files/IsComponent.04'); }
    public function testFiles_IsComponent05()  { $this->generic_test('Files/IsComponent.05'); }
    public function testFiles_IsComponent06()  { $this->generic_test('Files/IsComponent.06'); }
    public function testFiles_IsComponent07()  { $this->generic_test('Files/IsComponent.07'); }
}
?>