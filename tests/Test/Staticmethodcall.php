<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticmethodcall extends Tokenizeur {
    /* 5 methods */

    public function testStaticmethodcall01()  { $this->generic_test('Staticmethodcall.01'); }
    public function testStaticmethodcall02()  { $this->generic_test('Staticmethodcall.02'); }
    public function testStaticmethodcall03()  { $this->generic_test('Staticmethodcall.03'); }
    public function testStaticmethodcall04()  { $this->generic_test('Staticmethodcall.04'); }
    public function testStaticmethodcall05()  { $this->generic_test('Staticmethodcall.05'); }
}
?>