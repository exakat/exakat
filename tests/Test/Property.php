<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Property extends Tokenizeur {
    /* 2 methods */

    public function testProperty01()  { $this->generic_test('Property.01'); }
    public function testProperty02()  { $this->generic_test('Property.02'); }
}
?>