<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndefinedInterfaces extends Analyzer {
    /* 10 methods */

    public function testInterfaces_UndefinedInterfaces01()  { $this->generic_test('Interfaces_UndefinedInterfaces.01'); }
    public function testInterfaces_UndefinedInterfaces02()  { $this->generic_test('Interfaces_UndefinedInterfaces.02'); }
    public function testInterfaces_UndefinedInterfaces03()  { $this->generic_test('Interfaces_UndefinedInterfaces.03'); }
    public function testInterfaces_UndefinedInterfaces04()  { $this->generic_test('Interfaces_UndefinedInterfaces.04'); }
    public function testInterfaces_UndefinedInterfaces05()  { $this->generic_test('Interfaces/UndefinedInterfaces.05'); }
    public function testInterfaces_UndefinedInterfaces06()  { $this->generic_test('Interfaces/UndefinedInterfaces.06'); }
    public function testInterfaces_UndefinedInterfaces07()  { $this->generic_test('Interfaces/UndefinedInterfaces.07'); }
    public function testInterfaces_UndefinedInterfaces08()  { $this->generic_test('Interfaces/UndefinedInterfaces.08'); }
    public function testInterfaces_UndefinedInterfaces09()  { $this->generic_test('Interfaces/UndefinedInterfaces.09'); }
    public function testInterfaces_UndefinedInterfaces10()  { $this->generic_test('Interfaces/UndefinedInterfaces.10'); }
}
?>