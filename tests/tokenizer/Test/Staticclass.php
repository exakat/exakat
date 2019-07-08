<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticclass extends Tokenizer {
    /* 5 methods */

    public function testStaticclass01()  { $this->generic_test('Staticclass.01'); }
    public function testStaticclass02()  { $this->generic_test('Staticclass.02'); }
    public function testStaticclass03()  { $this->generic_test('Staticclass.03'); }
    public function testStaticclass04()  { $this->generic_test('Staticclass.04'); }
    public function testStaticclass05()  { $this->generic_test('Staticclass.05'); }
}
?>