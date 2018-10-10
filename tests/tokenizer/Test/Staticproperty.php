<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticproperty extends Tokenizer {
    /* 12 methods */

    public function testStaticproperty01()  { $this->generic_test('Staticproperty.01'); }
    public function testStaticproperty02()  { $this->generic_test('Staticproperty.02'); }
    public function testStaticproperty03()  { $this->generic_test('Staticproperty.03'); }
    public function testStaticproperty04()  { $this->generic_test('Staticproperty.04'); }
    public function testStaticproperty05()  { $this->generic_test('Staticproperty.05'); }
    public function testStaticproperty06()  { $this->generic_test('Staticproperty.06'); }
    public function testStaticproperty07()  { $this->generic_test('Staticproperty.07'); }
    public function testStaticproperty08()  { $this->generic_test('Staticproperty.08'); }
    public function testStaticproperty09()  { $this->generic_test('Staticproperty.09'); }
    public function testStaticproperty10()  { $this->generic_test('Staticproperty.10'); }
    public function testStaticproperty11()  { $this->generic_test('Staticproperty.11'); }
    public function testStaticproperty12()  { $this->generic_test('Staticproperty.12'); }
}
?>