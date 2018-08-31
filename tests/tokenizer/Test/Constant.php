<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Constant extends Tokenizer {
    /* 17 methods */
    public function testConstant01()  { $this->generic_test('Constant.01'); }
    public function testConstant02()  { $this->generic_test('Constant.02'); }
    public function testConstant03()  { $this->generic_test('Constant.03'); }
    public function testConstant04()  { $this->generic_test('Constant.04'); }
    public function testConstant05()  { $this->generic_test('Constant.05'); }
    public function testConstant06()  { $this->generic_test('Constant.06'); }
    public function testConstant07()  { $this->generic_test('Constant.07'); }
    public function testConstant08()  { $this->generic_test('Constant.08'); }
    public function testConstant09()  { $this->generic_test('Constant.09'); }
    public function testConstant10()  { $this->generic_test('Constant.10'); }
    public function testConstant11()  { $this->generic_test('Constant.11'); }
    public function testConstant12()  { $this->generic_test('Constant.12'); }
    public function testConstant13()  { $this->generic_test('Constant.13'); }
    public function testConstant14()  { $this->generic_test('Constant.14'); }
    public function testConstant15()  { $this->generic_test('Constant.15'); }
    public function testConstant16()  { $this->generic_test('Constant.16'); }

    public function testConstant17()  { $this->generic_test('Constant.17'); }
}
?>