<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Ifthen extends Tokenizeur {
    /* 12 methods */

    public function testIfthen01()  { $this->generic_test('Ifthen.01'); }
    public function testIfthen02()  { $this->generic_test('Ifthen.02'); }
    public function testIfthen03()  { $this->generic_test('Ifthen.03'); }

    public function testIfthen04()  { $this->generic_test('Ifthen.04'); }
    public function testIfthen05()  { $this->generic_test('Ifthen.05'); }
    public function testIfthen06()  { $this->generic_test('Ifthen.06'); }
    public function testIfthen07()  { $this->generic_test('Ifthen.07'); }
    public function testIfthen08()  { $this->generic_test('Ifthen.08'); }
    public function testIfthen09()  { $this->generic_test('Ifthen.09'); }
    public function testIfthen10()  { $this->generic_test('Ifthen.10'); }
    public function testIfthen11()  { $this->generic_test('Ifthen.11'); }
    public function testIfthen12()  { $this->generic_test('Ifthen.12'); }
}
?>