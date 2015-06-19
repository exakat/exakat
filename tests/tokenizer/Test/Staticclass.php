<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticclass extends Tokenizer {
    /* 2 methods */

    public function testStaticclass01()  { $this->generic_test('Staticclass.01'); }
    public function testStaticclass02()  { $this->generic_test('Staticclass.02'); }
}
?>