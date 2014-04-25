<?php

namespace Test;

class Analyzer extends \PHPUnit_Framework_TestCase {
    public function setUp() {
// @doc scripts/clean is safer, but longer
//        shell_exec("cd ../..; sh scripts/clean.sh");
// @doc delete is faster, and will leave the query cache on. 
        shell_exec("cd ../..; php bin/delete -all");
    }

    public function tearDown() {
        // empty
    }
    
    public function generic_test($file) {
        list($analyzer, $number) = explode('.', $file);
        $analyzer = str_replace('_', '/', $analyzer);
        
        $ini = parse_ini_file('../../projects/test/config.ini');
        $phpversion = empty($ini['phpversion']) ? phpversion() : $ini['phpversion'];
        $test_config = 'Analyzer'.str_replace('_', '\\', str_replace('Test', '', get_class($this)));

        $analyzerobject = new $test_config(null);
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        $Php = new \Phpexec($phpversion);
        if (!$analyzerobject->checkPhpConfiguration($Php)) {
            $message = array();
            $confs = $analyzerobject->getPhpConfiguration();
            if (is_array($confs)) {
                foreach($confs as $name => $value) {
                    $confs[] = "$name => $value";
                }
                $confs = join(', ', $confs);
            }
            
            $this->markTestSkipped('Needs configuration : '.$confs.'.');
        }
        
        $shell = 'cd ../..; php bin/load -q -p test -f tests/analyzer/source/'.$file.'.php';
        
        $res = shell_exec($shell);
        $pos = strpos($res, "won't compile");
        
        if ($pos !== false) {
            $this->assertFalse(true, 'test '.$file.' can\'t compile with PHP version "'. ($phpversion).'", so no test is being run.');
        }
        
        $shell = 'cd ../..;  php bin/build_root -p test; php bin/tokenizer -p test;  php bin/analyze -P '.escapeshellarg($test_config);
        $res = shell_exec($shell);

        $shell = 'cd ../..; php bin/export_analyzer '.$analyzer.' -o -json';
        $shell_res = shell_exec($shell);
        $res = json_decode($shell_res);

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