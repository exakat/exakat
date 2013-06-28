<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Comparison extends Tokenizeur {
    /* 13 methods */

    public function testComparison01()  { $this->generic_test('Comparison.01'); }
    public function testComparison02()  { $this->generic_test('Comparison.02'); }
    public function testComparison03()  { $this->generic_test('Comparison.03'); }
    public function testComparison04()  { $this->generic_test('Comparison.04'); }
    public function testComparison05()  { $this->generic_test('Comparison.05'); }
    public function testComparison06()  { $this->generic_test('Comparison.06'); }
    public function testComparison07()  { $this->generic_test('Comparison.07'); }
    public function testComparison08()  { $this->generic_test('Comparison.08'); }
    public function testComparison09()  { $this->generic_test('Comparison.09'); }
    public function testComparison10()  { $this->generic_test('Comparison.10'); }
    public function testComparison11()  { $this->generic_test('Comparison.11'); }
    public function testComparison12()  { $this->generic_test('Comparison.12'); }
    public function testComparison13()  { $this->generic_test('Comparison.13'); }
}
?>