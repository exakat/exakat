<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticproperty extends Tokenizeur {
    /* 5 methods */

    public function testStaticproperty01()  { $this->generic_test('Staticproperty.01'); }
    public function testStaticproperty02()  { $this->generic_test('Staticproperty.02'); }
    public function testStaticproperty03()  { $this->generic_test('Staticproperty.03'); }
    public function testStaticproperty04()  { $this->generic_test('Staticproperty.04'); }
    public function testStaticproperty05()  { $this->generic_test('Staticproperty.05'); }
}
?>