<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticproperty extends Tokenizeur {
    /* 3 methods */

    public function testStaticproperty01()  { $this->generic_test('Staticproperty.01'); }
    public function testStaticproperty02()  { $this->generic_test('Staticproperty.02'); }
    public function testStaticproperty03()  { $this->generic_test('Staticproperty.03'); }
}
?>