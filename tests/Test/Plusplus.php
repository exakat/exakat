<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Plusplus extends Tokenizeur {
    /* 16 methods */

    public function testPlusplus01()  { $this->generic_test('Plusplus.01'); }
    public function testPlusplus02()  { $this->generic_test('Plusplus.02'); }
    public function testPlusplus03()  { $this->generic_test('Plusplus.03'); }
    public function testPlusplus04()  { $this->generic_test('Plusplus.04'); }
    public function testPlusplus05()  { $this->generic_test('Plusplus.05'); }
    public function testPlusplus06()  { $this->generic_test('Plusplus.06'); }
    public function testPlusplus07()  { $this->generic_test('Plusplus.07'); }
    public function testPlusplus08()  { $this->generic_test('Plusplus.08'); }
    public function testPlusplus09()  { $this->generic_test('Plusplus.09'); }
    public function testPlusplus10()  { $this->generic_test('Plusplus.10'); }
    public function testPlusplus11()  { $this->generic_test('Plusplus.11'); }
    public function testPlusplus12()  { $this->generic_test('Plusplus.12'); }
    public function testPlusplus13()  { $this->generic_test('Plusplus.13'); }
    public function testPlusplus14()  { $this->generic_test('Plusplus.14'); }
    public function testPlusplus15()  { $this->generic_test('Plusplus.15'); }
    public function testPlusplus16()  { $this->generic_test('Plusplus.16'); }

}
?>
