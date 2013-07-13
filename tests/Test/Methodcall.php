<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Methodcall extends Tokenizeur {
    /* 3 methods */

    public function testMethodcall01()  { $this->generic_test('Methodcall.01'); }
    public function testMethodcall02()  { $this->generic_test('Methodcall.02'); }
    public function testMethodcall03()  { $this->generic_test('Methodcall.03'); }
}
?>