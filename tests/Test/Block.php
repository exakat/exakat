<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Block extends Tokenizeur {
    /* 3 methods */

    public function testBlock01()  { $this->generic_test('Block.01'); }
    public function testBlock02()  { $this->generic_test('Block.02'); }
    public function testBlock03()  { $this->generic_test('Block.03'); }
}
?>