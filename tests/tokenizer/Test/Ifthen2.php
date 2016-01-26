<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Ifthen2 extends Tokenizer {
    /* 4 methods */

    public function testIfthen201()  { $this->generic_test('Ifthen2.01'); }
    public function testIfthen202()  { $this->generic_test('Ifthen2.02'); }
    public function testIfthen203()  { $this->generic_test('Ifthen2.03'); }
    public function testIfthen204()  { $this->generic_test('Ifthen2.04'); }
}
?>