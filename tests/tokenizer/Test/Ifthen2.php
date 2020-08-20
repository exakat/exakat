<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Ifthen2 extends Tokenizer {
    /* 9 methods */

    public function testIfthen201()  { $this->generic_test('Ifthen2.01'); }
    public function testIfthen202()  { $this->generic_test('Ifthen2.02'); }
    public function testIfthen203()  { $this->generic_test('Ifthen2.03'); }
    public function testIfthen204()  { $this->generic_test('Ifthen2.04'); }
    public function testIfthen205()  { $this->generic_test('Ifthen2.05'); }
    public function testIfthen206()  { $this->generic_test('Ifthen2.06'); }
    public function testIfthen207()  { $this->generic_test('Ifthen2.07'); }
    public function testIfthen208()  { $this->generic_test('Ifthen2.08'); }
    public function testIfthen209()  { $this->generic_test('Ifthen2.09'); }
}
?>