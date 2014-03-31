<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Structures_ForgottenWhiteSpace extends Analyzer {
    /* 5 methods */

    public function testStructures_ForgottenWhiteSpace01()  { $this->generic_test('Structures_ForgottenWhiteSpace.01'); }
    public function testStructures_ForgottenWhiteSpace02()  { $this->generic_test('Structures_ForgottenWhiteSpace.02'); }
    public function testStructures_ForgottenWhiteSpace03()  { $this->generic_test('Structures_ForgottenWhiteSpace.03'); }
    public function testStructures_ForgottenWhiteSpace04()  { $this->generic_test('Structures_ForgottenWhiteSpace.04'); }
    public function testStructures_ForgottenWhiteSpace05()  { $this->generic_test('Structures_ForgottenWhiteSpace.05'); }
}
?>