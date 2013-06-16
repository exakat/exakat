<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Object extends Tokenizeur {
    /* 1 methods */
    public function testObject01()  { $this->generic_test('Object.01'); }
    public function testObject02()  { $this->generic_test('Object.02'); }
    public function testObject03()  { $this->generic_test('Object.03'); }
    public function testObject04()  { $this->generic_test('Object.04'); }
    public function testObject05()  { $this->generic_test('Object.05'); }
    public function testObject06()  { $this->generic_test('Object.06'); }
    public function testObject07()  { $this->generic_test('Object.07'); }
}
?>