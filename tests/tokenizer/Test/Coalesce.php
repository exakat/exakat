<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Coalesce extends Tokenizer {
    /* 9 methods */

    public function testCoalesce01()  { $this->generic_test('Coalesce.01'); }
    public function testCoalesce02()  { $this->generic_test('Coalesce.02'); }
    public function testCoalesce03()  { $this->generic_test('Coalesce.03'); }
    public function testCoalesce04()  { $this->generic_test('Coalesce.04'); }
    public function testCoalesce05()  { $this->generic_test('Coalesce.05'); }
    public function testCoalesce06()  { $this->generic_test('Coalesce.06'); }
    public function testCoalesce07()  { $this->generic_test('Coalesce.07'); }
    public function testCoalesce08()  { $this->generic_test('Coalesce.08'); }
    public function testCoalesce09()  { $this->generic_test('Coalesce.09'); }
}
?>