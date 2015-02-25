<?php

namespace Test;

class Analyzer extends \PHPUnit_Framework_TestCase {
    public function generic_test($file) {
        list($analyzer, $number) = explode('.', $file);
        $analyzer = str_replace('_', '/', $analyzer);
        
        $ini = parse_ini_file('../../projects/test/config.ini');
        $phpversion = empty($ini['phpversion']) ? phpversion() : $ini['phpversion'];
        $test_config = 'Analyzer'.str_replace('_', '\\', substr(get_class($this), 4));

        $analyzerobject = new $test_config(null);
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        // initialize Config (needed by phpexec)
        \Config::factory(array('foo', '-p', 'test'));
        
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
        
        $shell = 'cd ../..; php exakat load -p test -f tests/analyzer/source/'.$file.'.php';
        
        $res = shell_exec($shell);
        if (strpos($res, "won't compile") !== false) {
            $this->assertFalse(true, 'test '.$file.' can\'t compile with PHP version "'. ($phpversion).'", so no test is being run.');
        }
        
        $shell = 'cd ../..;  php exakat build_root -p test; php exakat tokenizer -p test;  php exakat analyze -P '.escapeshellarg($test_config).' -p test ';
        $res = shell_exec($shell);

        $shell = 'cd ../..; php exakat results  -p test -P '.$analyzer.' -o -json';
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