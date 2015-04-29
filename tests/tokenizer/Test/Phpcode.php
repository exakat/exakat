<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Phpcode extends Tokenizer {
    /* 16 methods */

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
    public function testPhpcode12()  { $this->generic_test('Phpcode.12'); }
    public function testPhpcode13()  { $this->generic_test('Phpcode.13'); }
    public function testPhpcode14()  { $this->generic_test('Phpcode.14'); }
    public function testPhpcode15()  { $this->generic_test('Phpcode.15'); }
    public function testPhpcode16()  { $this->generic_test('Phpcode.16'); }
}
?>