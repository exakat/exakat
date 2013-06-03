<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Addition extends Tokenizeur {
    /* 5 methods */
    public function testAddition01()  { $this->generic_test('Addition.01'); }
    public function testAddition02()  { $this->generic_test('Addition.02'); }
    public function testAddition03()  { $this->generic_test('Addition.03'); }
    public function testAddition04()  { $this->generic_test('Addition.04'); }
    public function testAddition05()  { $this->generic_test('Addition.05'); }
}

?>