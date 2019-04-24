<?php

use PHPUnit\Framework\TestSuite;

include_once __DIR__.'/Test/Analyzer.php';

class AllTests extends TestSuite {

    public static function suite() {

        $tests = glob(__DIR__.'/Test/*/*.php');

        $classes = count($tests);
        $total = (int) shell_exec('grep -r "public function test" '.__DIR__.'/Test/*/*.php | wc -l');
        $fp = fopen(__DIR__.'/alltests.csv', 'a');
        fwrite($fp, "\"".date('r')."\"\t\"$classes\"\t\"$total\"\n");
        fclose($fp);

        return Test\testSuiteBuilder($tests);
    }
}
?>