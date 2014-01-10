<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Phpcode extends Tokenizer {
    /* 11 methods */

    public function testPhpcode01()  { $this->generic_test('Phpcode.01'); }
    public function testPhpcode02()  { $this->generic_test('Phpcode.02'); }
    public function testPhpcode03()  { $this->generic_test('Phpcode.03'); }
    public function testPhpcode04()  { $this->generic_test('Phpcode.04'); }
    public function testPhpcode05()  { $this->generic_test('Phpcode.05'); }
    public function testPhpcode06()  { $this->generic_test('Phpcode.06'); }
    public function testPhpcode07()  { $this->generic_test('Phpcode.07'); }
    public function testPhpcode08()  { $this->generic_test('Phpcode.08'); }
    public function testPhpcode09()  { $this->generic_test('Phpcode.09'); }
    public function testPhpcode10()  { $this->generic_test('Phpcode.10'); }
    public function testPhpcode11()  { $this->generic_test('Phpcode.11'); }
}
?>