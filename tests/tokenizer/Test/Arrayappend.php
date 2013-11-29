<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Arrayappend extends Tokenizer {
    /* 5 methods */

    public function testArrayappend01()  { $this->generic_test('Arrayappend.01'); }
    public function testArrayappend02()  { $this->generic_test('Arrayappend.02'); }
    public function testArrayappend03()  { $this->generic_test('Arrayappend.03'); }
    public function testArrayappend04()  { $this->generic_test('Arrayappend.04'); }
    public function testArrayappend05()  { $this->generic_test('Arrayappend.05'); }
}
?>