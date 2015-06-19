<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Cast extends Tokenizer {
    /* 24 methods */

    public function testCast01()  { $this->generic_test('Cast.01'); }
    public function testCast02()  { $this->generic_test('Cast.02'); }
    public function testCast03()  { $this->generic_test('Cast.03'); }
    public function testCast04()  { $this->generic_test('Cast.04'); }
    public function testCast05()  { $this->generic_test('Cast.05'); }
    public function testCast06()  { $this->generic_test('Cast.06'); }
    public function testCast07()  { $this->generic_test('Cast.07'); }
    public function testCast08()  { $this->generic_test('Cast.08'); }
    public function testCast09()  { $this->generic_test('Cast.09'); }
    public function testCast10()  { $this->generic_test('Cast.10'); }
    public function testCast11()  { $this->generic_test('Cast.11'); }
    public function testCast12()  { $this->generic_test('Cast.12'); }
    public function testCast13()  { $this->generic_test('Cast.13'); }
    public function testCast14()  { $this->generic_test('Cast.14'); }
    public function testCast15()  { $this->generic_test('Cast.15'); }
    public function testCast16()  { $this->generic_test('Cast.16'); }
    public function testCast17()  { $this->generic_test('Cast.17'); }
    public function testCast18()  { $this->generic_test('Cast.18'); }
    public function testCast19()  { $this->generic_test('Cast.19'); }
    public function testCast20()  { $this->generic_test('Cast.20'); }
    public function testCast21()  { $this->generic_test('Cast.21'); }
    public function testCast22()  { $this->generic_test('Cast.22'); }
    public function testCast23()  { $this->generic_test('Cast.23'); }
    public function testCast24()  { $this->generic_test('Cast.24'); }
}
?>