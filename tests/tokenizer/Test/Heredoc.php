<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Heredoc extends Tokenizer {
    /* 7 methods */

    public function testHeredoc01()  { $this->generic_test('Heredoc.01'); }
    public function testHeredoc02()  { $this->generic_test('Heredoc.02'); }
    public function testHeredoc03()  { $this->generic_test('Heredoc.03'); }
    public function testHeredoc04()  { $this->generic_test('Heredoc.04'); }
    public function testHeredoc05()  { $this->generic_test('Heredoc.05'); }
    public function testHeredoc06()  { $this->generic_test('Heredoc.06'); }
    public function testHeredoc07()  { $this->generic_test('Heredoc.07'); }
}
?>