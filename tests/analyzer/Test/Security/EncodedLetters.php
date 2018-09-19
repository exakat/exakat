<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class EncodedLetters extends Analyzer {
    /* 5 methods */

    public function testSecurity_EncodedLetters01()  { $this->generic_test('Security/EncodedLetters.01'); }
    public function testSecurity_EncodedLetters02()  { $this->generic_test('Security/EncodedLetters.02'); }
    public function testSecurity_EncodedLetters03()  { $this->generic_test('Security/EncodedLetters.03'); }
    public function testSecurity_EncodedLetters04()  { $this->generic_test('Security/EncodedLetters.04'); }
    public function testSecurity_EncodedLetters05()  { $this->generic_test('Security/EncodedLetters.05'); }
}
?>