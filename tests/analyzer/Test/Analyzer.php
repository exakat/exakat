<?php

namespace Test;

use Exakat\Phpexec;
use Exakat\Analyzer\Analyzer as ExakatAnalyzer;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');

class Analyzer extends \PHPUnit_Framework_TestCase {
    public function generic_test($file) {
        list($analyzer, $number) = explode('.', $file);
        $analyzer = str_replace('_', '/', $analyzer);
        
        // Test are run with test project.
        $ini = parse_ini_file('../../projects/test/config.ini');
        $phpversion = empty($ini['phpversion']) ? phpversion() : $ini['phpversion'];
        $test_config = str_replace('_', '/', substr(get_class($this), 5));

        $analyzerobject = ExakatAnalyzer::getInstance($test_config);
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        require('exp/'.str_replace('_', '/', $file).'.php');

        // initialize Config (needed by phpexec)
        $config = \Exakat\Config::factory(array('foo', '-p', 'test'));
        
        $versionPHP = 'php'.str_replace('.', '', $phpversion);
        $res = shell_exec($config->$versionPHP.' -l ./source/'.str_replace('_', '/', $file).'.php 2>/dev/null');
        if (strpos($res, 'No syntax errors detected') === false) {
            $this->markTestSkipped('Compilation problem : "'.$res.'".');
        }
        
        $Php = new Phpexec($phpversion);
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
        
        $analyzer = escapeshellarg($test_config);
        $source = 'source/'.str_replace('_', '/', $file).'.php';

        if (is_dir($source)) {
            $shell = 'cd ../..; php exakat test -d ./tests/analyzer/'.$source.' -P '.$analyzer.'; php exakat results  -p test -P '.$analyzer.' -o -json';
        } else {
            $shell = 'cd ../..; php exakat test -f ./tests/analyzer/'.$source.' -P '.$analyzer.'; php exakat results  -p test -P '.$analyzer.' -o -json';
        }
        $shell_res = shell_exec($shell);
        $res = json_decode($shell_res);
        if ($res === null) {
            $this->assertTrue(false, "Json couldn't be decoded : '$shell_res'");
        }

        if (empty($res)) {
            $list = array();
        } else {
            $list = array();
            foreach($res as $r) {
                $list[] = $r[0];
            }
            $this->assertNotEquals(count($list), 0, 'No values were read from the analyzer' );
        }
        
        if (isset($expected) && is_array($expected)) {
            $missing = array();
            foreach($expected as $e) {
                if (($id = array_search($e, $list)) !== false) {
                    unset($list[$id]);
                } else {
                    $missing[] = $e;
                }
            }
            $list = array_map(function ($x) { return str_replace("'", "\\'", $x); }, $list);
            $this->assertEquals(count($missing), 0, count($missing)." expected values were not found :\n '".join("',\n '", $missing)."'\n\nin the ".count($list)." received values of \n '".join("', \n '", $list)."'

source/$file.php
exp/$file.php
phpunit --filter=$number Test/$analyzer.php

");
            // also add a phpunit --filter to rerun it easily
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