<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Property extends Tokenizer {
    /* 25 methods */

    public function testProperty01()  { $this->generic_test('Property.01'); }
    public function testProperty02()  { $this->generic_test('Property.02'); }
    public function testProperty03()  { $this->generic_test('Property.03'); }
    public function testProperty04()  { $this->generic_test('Property.04'); }
    public function testProperty05()  { $this->generic_test('Property.05'); }
    public function testProperty06()  { $this->generic_test('Property.06'); }
    public function testProperty07()  { $this->generic_test('Property.07'); }
    public function testProperty08()  { $this->generic_test('Property.08'); }
    public function testProperty09()  { $this->generic_test('Property.09'); }
    public function testProperty10()  { $this->generic_test('Property.10'); }
    public function testProperty11()  { $this->generic_test('Property.11'); }
    public function testProperty12()  { $this->generic_test('Property.12'); }
    public function testProperty13()  { $this->generic_test('Property.13'); }
    public function testProperty14()  { $this->generic_test('Property.14'); }
    public function testProperty15()  { $this->generic_test('Property.15'); }
    public function testProperty16()  { $this->generic_test('Property.16'); }
    public function testProperty17()  { $this->generic_test('Property.17'); }
    public function testProperty18()  { $this->generic_test('Property.18'); }
    public function testProperty19()  { $this->generic_test('Property.19'); }
    public function testProperty20()  { $this->generic_test('Property.20'); }
    public function testProperty21()  { $this->generic_test('Property.21'); }
    public function testProperty22()  { $this->generic_test('Property.22'); }
    public function testProperty23()  { $this->generic_test('Property.23'); }
    public function testProperty24()  { $this->generic_test('Property.24'); }
    public function testProperty25()  { $this->generic_test('Property.25'); }
}
?>