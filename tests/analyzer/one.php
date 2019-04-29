<?php

use PHPUnit\Framework\TestSuite;

include_once __DIR__.'/Test/Analyzer.php';

class Onetest extends TestSuite {
    public static function suite() {
        $tests = glob(__DIR__.'/Test/Security/*.php');
        return Test\testSuiteBuilder($tests);
    }
}
?>