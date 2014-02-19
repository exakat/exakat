<?php

namespace Test;

class Analyzer extends \PHPUnit_Framework_TestCase {
    function setUp() {
// @doc scripts/clean is safer, but longer
//        shell_exec("cd ../..; sh scripts/clean.sh");
// @doc delete is faster, and will leave the query cache on. 
        shell_exec("cd ../..; php bin/delete -all");
    }

    function tearDown() {
        // empty
    }
    
    function generic_test($file) {
        list($analyzer, $number) = explode('.', $file);
        
        $test_config = 'Analyzer\\'.str_replace('_', '\\\\', str_replace('Test', '', get_class($this)));
        $shell = 'cd ../..; php bin/load -p test -f tests/analyzer/source/'.$file.'.php; php bin/build_root -p test; php bin/tokenizer -p test;  php bin/fullcode -p test; php bin/analyze -P '.$test_config;
        $res = shell_exec($shell);
        $shell = 'cd ../..; php bin/export_analyzer '.$analyzer.' -o -json';
        $res = json_decode(shell_exec($shell));

        if (empty($res)) {
            $list = array();
        } else {
            $list = array();
            foreach($res as $r) {
                $list[] = $r[0];
            }
            $this->assertNotEquals(count($list), 0, 'No values were read from the analyzer' );
        }
        
        include('exp/'.$file.'.php');
        
        if (isset($expected) && is_array($expected)) {
            $missing = array();
            foreach($expected as $e) {
                if (($id = array_search($e, $list)) !== false) {
                    unset($list[$id]);
                } else {
                    $missing[] = $e;
                }
            }
            $this->assertEquals(count($missing), 0, count($missing)." expected values were not found : '".join("', '", $missing)."' in the received values of '".join("', '", $list)."'");
        }
        
        if (isset($expected_not) && is_array($expected)) {
            $extra = array();
            foreach($expected_not as $e) {
                if ($id = array_search($e, $list)) {
                    $extra[] = $e;
                    unset($list[$id]);
                } 
            }
            // the not expected
            $this->assertEquals(count($extra), 0, count($extra)." values were found and shouldn't be : ".join(', ', $extra)."");
        }
        
        // the remainings
        $this->assertEquals(count($list), 0, count($list)." values were found and are unprocessed : ".join(', ', $list)."");
    }
    
    
}

?>