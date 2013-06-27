<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Ifthen extends Tokenizeur {
    /* 3 methods */

    public function testIfthen01()  { $this->generic_test('Ifthen.01'); }
    public function testIfthen02()  { $this->generic_test('Ifthen.02'); }
    public function testIfthen03()  { $this->generic_test('Ifthen.03'); }

}
?>