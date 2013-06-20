<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Assignation extends Tokenizeur {
    /* 5 methods */
    public function testAssignation01()  { $this->generic_test('Assignation.01'); }
    public function testAssignation02()  { $this->generic_test('Assignation.02'); }
    public function testAssignation03()  { $this->generic_test('Assignation.03'); }
    public function testAssignation04()  { $this->generic_test('Assignation.04'); }
    public function testAssignation05()  { $this->generic_test('Assignation.05'); }
}
?>