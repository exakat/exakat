<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functioncall extends Tokenizeur {
    /* 15 methods */

    public function testFunctioncall01()  { $this->generic_test('Functioncall.01'); }
    public function testFunctioncall02()  { $this->generic_test('Functioncall.02'); }
    public function testFunctioncall03()  { $this->generic_test('Functioncall.03'); }
    public function testFunctioncall04()  { $this->generic_test('Functioncall.04'); }
    public function testFunctioncall05()  { $this->generic_test('Functioncall.05'); }
    public function testFunctioncall06()  { $this->generic_test('Functioncall.06'); }
    public function testFunctioncall07()  { $this->generic_test('Functioncall.07'); }
    public function testFunctioncall08()  { $this->generic_test('Functioncall.08'); }
    public function testFunctioncall09()  { $this->generic_test('Functioncall.09'); }
    public function testFunctioncall10()  { $this->generic_test('Functioncall.10'); }
    public function testFunctioncall11()  { $this->generic_test('Functioncall.11'); }
    public function testFunctioncall12()  { $this->generic_test('Functioncall.12'); }
    public function testFunctioncall13()  { $this->generic_test('Functioncall.13'); }
    public function testFunctioncall14()  { $this->generic_test('Functioncall.14'); }
    public function testFunctioncall15()  { $this->generic_test('Functioncall.15'); }
}
?>