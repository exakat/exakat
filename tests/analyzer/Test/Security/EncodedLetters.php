<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EncodedLetters extends Analyzer {
    /* 5 methods */

    public function testSecurity_EncodedLetters01()  { $this->generic_test('Security/EncodedLetters.01'); }
    public function testSecurity_EncodedLetters02()  { $this->generic_test('Security/EncodedLetters.02'); }
    public function testSecurity_EncodedLetters03()  { $this->generic_test('Security/EncodedLetters.03'); }
    public function testSecurity_EncodedLetters04()  { $this->generic_test('Security/EncodedLetters.04'); }
    public function testSecurity_EncodedLetters05()  { $this->generic_test('Security/EncodedLetters.05'); }
}
?>