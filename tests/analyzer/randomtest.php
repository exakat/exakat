<?php

use PHPUnit\Framework\TestSuite;

include_once __DIR__.'/Test/Analyzer.php';

class Randomtest extends TestSuite {

    public static function suite() {
        $tests = glob(__DIR__.'/Test/*/*.php');
        
        $tests = array_filter($tests, function ($x) { return strpos($x, 'Complete') === false; });
        shuffle($tests);
        $tests = array_slice($tests, 0, 100);

        return Test\testSuiteBuilder($tests);
    }
}
?>