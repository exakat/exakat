<?php

use PHPUnit\Framework\TestSuite;

include_once __DIR__.'/Test/Analyzer.php';

class Framework_Onetest extends TestSuite {

    public static function suite() {
        $tests = glob(__DIR__.'/Test/Type/Path.php');
        return Test\testSuiteBuilder($tests);
    }
}
?>