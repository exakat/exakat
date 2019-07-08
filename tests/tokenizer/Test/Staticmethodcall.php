<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticmethodcall extends Tokenizer {
    /* 22 methods */

    public function testStaticmethodcall01()  { $this->generic_test('Staticmethodcall.01'); }
    public function testStaticmethodcall02()  { $this->generic_test('Staticmethodcall.02'); }
    public function testStaticmethodcall03()  { $this->generic_test('Staticmethodcall.03'); }
    public function testStaticmethodcall04()  { $this->generic_test('Staticmethodcall.04'); }
    public function testStaticmethodcall05()  { $this->generic_test('Staticmethodcall.05'); }
    public function testStaticmethodcall06()  { $this->generic_test('Staticmethodcall.06'); }
    public function testStaticmethodcall07()  { $this->generic_test('Staticmethodcall.07'); }
    public function testStaticmethodcall08()  { $this->generic_test('Staticmethodcall.08'); }
    public function testStaticmethodcall09()  { $this->generic_test('Staticmethodcall.09'); }
    public function testStaticmethodcall10()  { $this->generic_test('Staticmethodcall.10'); }
    public function testStaticmethodcall11()  { $this->generic_test('Staticmethodcall.11'); }
    public function testStaticmethodcall12()  { $this->generic_test('Staticmethodcall.12'); }
    public function testStaticmethodcall13()  { $this->generic_test('Staticmethodcall.13'); }
    public function testStaticmethodcall14()  { $this->generic_test('Staticmethodcall.14'); }
    public function testStaticmethodcall15()  { $this->generic_test('Staticmethodcall.15'); }
    public function testStaticmethodcall16()  { $this->generic_test('Staticmethodcall.16'); }
    public function testStaticmethodcall17()  { $this->generic_test('Staticmethodcall.17'); }
    public function testStaticmethodcall18()  { $this->generic_test('Staticmethodcall.18'); }
    public function testStaticmethodcall19()  { $this->generic_test('Staticmethodcall.19'); }
    public function testStaticmethodcall20()  { $this->generic_test('Staticmethodcall.20'); }
    public function testStaticmethodcall21()  { $this->generic_test('Staticmethodcall.21'); }
    public function testStaticmethodcall22()  { $this->generic_test('Staticmethodcall.22'); }
}
?>