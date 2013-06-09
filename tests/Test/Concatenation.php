<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Concatenation extends Tokenizeur {
    /* 2 methods */
    public function testConcatenation01()  { $this->generic_test('Concatenation.01'); }
    public function testConcatenation02()  { $this->generic_test('Concatenation.02'); }
    public function testConcatenation03()  { $this->generic_test('Concatenation.03'); }
}
?>