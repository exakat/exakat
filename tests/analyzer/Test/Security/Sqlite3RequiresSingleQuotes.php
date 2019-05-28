<?php

namespace Test\Security;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Sqlite3RequiresSingleQuotes extends Analyzer {
    /* 3 methods */

    public function testSecurity_Sqlite3RequiresSingleQuotes01()  { $this->generic_test('Security/Sqlite3RequiresSingleQuotes.01'); }
    public function testSecurity_Sqlite3RequiresSingleQuotes02()  { $this->generic_test('Security/Sqlite3RequiresSingleQuotes.02'); }
    public function testSecurity_Sqlite3RequiresSingleQuotes03()  { $this->generic_test('Security/Sqlite3RequiresSingleQuotes.03'); }
}
?>