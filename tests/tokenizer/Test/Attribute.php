<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Attribute extends Tokenizer {
    /* 7 methods */

    public function testAttribute01()  { $this->generic_test('Attribute.01'); }
    public function testAttribute02()  { $this->generic_test('Attribute.02'); }
    public function testAttribute03()  { $this->generic_test('Attribute.03'); }
    public function testAttribute04()  { $this->generic_test('Attribute.04'); }
    public function testAttribute05()  { $this->generic_test('Attribute.05'); }
    public function testAttribute06()  { $this->generic_test('Attribute.06'); }
    public function testAttribute07()  { $this->generic_test('Attribute.07'); }
}
?>