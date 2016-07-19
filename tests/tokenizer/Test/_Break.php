<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Break extends Tokenizer {
    /* 8 methods */

    public function test_Break01()  { $this->generic_test('_Break.01'); }
    public function test_Break02()  { $this->generic_test('_Break.02'); }
    public function test_Break03()  { $this->generic_test('_Break.03'); }
    public function test_Break04()  { $this->generic_test('_Break.04'); }
    public function test_Break05()  { $this->generic_test('_Break.05'); }
    public function test_Break06()  { $this->generic_test('_Break.06'); }
    public function test_Break07()  { $this->generic_test('_Break.07'); }
    public function test_Break08()  { $this->generic_test('_Break.08'); }
}
?>