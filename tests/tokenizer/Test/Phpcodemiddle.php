<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Phpcodemiddle extends Tokenizer {
    /* 8 methods */

    public function testPhpcodemiddle01()  { $this->generic_test('Phpcodemiddle.01'); }
    public function testPhpcodemiddle02()  { $this->generic_test('Phpcodemiddle.02'); }
    public function testPhpcodemiddle03()  { $this->generic_test('Phpcodemiddle.03'); }
    public function testPhpcodemiddle04()  { $this->generic_test('Phpcodemiddle.04'); }
    public function testPhpcodemiddle05()  { $this->generic_test('Phpcodemiddle.05'); }
    public function testPhpcodemiddle06()  { $this->generic_test('Phpcodemiddle.06'); }
    public function testPhpcodemiddle07()  { $this->generic_test('Phpcodemiddle.07'); }
    public function testPhpcodemiddle08()  { $this->generic_test('Phpcodemiddle.08'); }
}
?>