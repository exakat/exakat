<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Sequence extends Tokenizeur {
    /* 7 methods */

    public function testSequence01()  { $this->generic_test('Sequence.01'); }
    public function testSequence02()  { $this->generic_test('Sequence.02'); }
    public function testSequence03()  { $this->generic_test('Sequence.03'); }
    public function testSequence04()  { $this->generic_test('Sequence.04'); }
    public function testSequence05()  { $this->generic_test('Sequence.05'); }
    public function testSequence06()  { $this->generic_test('Sequence.06'); }
    public function testSequence07()  { $this->generic_test('Sequence.07'); }
}
?>