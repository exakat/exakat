<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Block extends Tokenizer {
    /* 17 methods */

    public function testBlock01()  { $this->generic_test('Block.01'); }
    public function testBlock02()  { $this->generic_test('Block.02'); }
    public function testBlock03()  { $this->generic_test('Block.03'); }
    public function testBlock04()  { $this->generic_test('Block.04'); }
    public function testBlock05()  { $this->generic_test('Block.05'); }
    public function testBlock06()  { $this->generic_test('Block.06'); }
    public function testBlock07()  { $this->generic_test('Block.07'); }
    public function testBlock08()  { $this->generic_test('Block.08'); }
    public function testBlock09()  { $this->generic_test('Block.09'); }
    public function testBlock10()  { $this->generic_test('Block.10'); }
    public function testBlock11()  { $this->generic_test('Block.11'); }
    public function testBlock12()  { $this->generic_test('Block.12'); }
    public function testBlock13()  { $this->generic_test('Block.13'); }
    public function testBlock14()  { $this->generic_test('Block.14'); }
    public function testBlock15()  { $this->generic_test('Block.15'); }
    public function testBlock16()  { $this->generic_test('Block.16'); }
    public function testBlock17()  { $this->generic_test('Block.17'); }
}
?>