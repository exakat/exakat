<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseInterface extends Analyzer {
    /* 8 methods */

    public function testInterfaces_CouldUseInterface01()  { $this->generic_test('Interfaces/CouldUseInterface.01'); }
    public function testInterfaces_CouldUseInterface02()  { $this->generic_test('Interfaces/CouldUseInterface.02'); }
    public function testInterfaces_CouldUseInterface03()  { $this->generic_test('Interfaces/CouldUseInterface.03'); }
    public function testInterfaces_CouldUseInterface04()  { $this->generic_test('Interfaces/CouldUseInterface.04'); }
    public function testInterfaces_CouldUseInterface05()  { $this->generic_test('Interfaces/CouldUseInterface.05'); }
    public function testInterfaces_CouldUseInterface06()  { $this->generic_test('Interfaces/CouldUseInterface.06'); }
    public function testInterfaces_CouldUseInterface07()  { $this->generic_test('Interfaces/CouldUseInterface.07'); }
    public function testInterfaces_CouldUseInterface08()  { $this->generic_test('Interfaces/CouldUseInterface.08'); }
}
?>