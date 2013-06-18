<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Noscream extends Tokenizeur {
    /* 8 methods */

    public function testNoscream01()  { $this->generic_test('Noscream.01'); }
    public function testNoscream02()  { $this->generic_test('Noscream.02'); }
    public function testNoscream03()  { $this->generic_test('Noscream.03'); }
    public function testNoscream04()  { $this->generic_test('Noscream.04'); }
    public function testNoscream05()  { $this->generic_test('Noscream.05'); }
    public function testNoscream06()  { $this->generic_test('Noscream.06'); }
    public function testNoscream07()  { $this->generic_test('Noscream.07'); }
    public function testNoscream08()  { $this->generic_test('Noscream.08'); }
}
?>