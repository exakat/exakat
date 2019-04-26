<?php

namespace Test\Exceptions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UncaughtExceptions extends Analyzer {
    /* 4 methods */

    public function testExceptions_UncaughtExceptions01()  { $this->generic_test('Exceptions/UncaughtExceptions.01'); }
    public function testExceptions_UncaughtExceptions02()  { $this->generic_test('Exceptions/UncaughtExceptions.02'); }
    public function testExceptions_UncaughtExceptions03()  { $this->generic_test('Exceptions/UncaughtExceptions.03'); }
    public function testExceptions_UncaughtExceptions04()  { $this->generic_test('Exceptions/UncaughtExceptions.04'); }
}
?>