<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Phpcodemiddle extends Tokenizer {
    /* 3 methods */

    public function testPhpcodemiddle01()  { $this->generic_test('Phpcodemiddle.01'); }
    public function testPhpcodemiddle02()  { $this->generic_test('Phpcodemiddle.02'); }
    public function testPhpcodemiddle03()  { $this->generic_test('Phpcodemiddle.03'); }
}
?>