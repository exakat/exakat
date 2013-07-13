<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Sequence extends Tokenizeur {
    /* 14 methods */

    public function testSequence01()  { $this->generic_test('Sequence.01'); }
    public function testSequence02()  { $this->generic_test('Sequence.02'); }
    public function testSequence03()  { $this->generic_test('Sequence.03'); }
    public function testSequence04()  { $this->generic_test('Sequence.04'); }
    public function testSequence05()  { $this->generic_test('Sequence.05'); }
    public function testSequence06()  { $this->generic_test('Sequence.06'); }
    public function testSequence07()  { $this->generic_test('Sequence.07'); }
    public function testSequence08()  { $this->generic_test('Sequence.08'); }
    public function testSequence09()  { $this->generic_test('Sequence.09'); }
    public function testSequence10()  { $this->generic_test('Sequence.10'); }
    public function testSequence11()  { $this->generic_test('Sequence.11'); }
    public function testSequence12()  { $this->generic_test('Sequence.12'); }
    public function testSequence13()  { $this->generic_test('Sequence.13'); }
    public function testSequence14()  { $this->generic_test('Sequence.14'); }
}
?>