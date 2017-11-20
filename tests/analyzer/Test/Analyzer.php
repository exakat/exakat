<?php

namespace Test;

use Exakat\Phpexec;
use Exakat\Analyzer\Analyzer as ExakatAnalyzer;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');

class Analyzer extends \PHPUnit_Framework_TestCase {
    public function generic_test($file) {
        if (preg_match('/^\w+_/', $file)) {
            $file = preg_replace('/^([^_]+?)_(.*)$/', '$1/$2', $file);
        }
        list($analyzer, $number) = explode('.', $file);
                
        // Test are run with test project.
        $ini = parse_ini_file('../../projects/test/config.ini');
        $phpversion = empty($ini['phpversion']) ? phpversion() : $ini['phpversion'];
        $test_config = preg_replace('/^([^_]+?)_(.*)$/', '$1/$2', substr(get_class($this), 5));

        // initialize Config (needed by phpexec)
        $pwd = getcwd();
        chdir('../../');
        $config = new \Exakat\Config(array('foo', '-p', 'test'));
        chdir($pwd);

        $analyzerobject = ExakatAnalyzer::getInstance($test_config, null, $config);
        if ($analyzerobject === null) {
            $this->markTestSkipped('Couldn\'t get an analyzer for '.$test_config.'.');
        }
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        require('exp/'.$file.'.php');
        
        $versionPHP = 'php'.str_replace('.', '', $phpversion);
        $res = shell_exec($config->$versionPHP.' -l ./source/'.$file.'.php 2>/dev/null');
        if (strpos($res, 'No syntax errors detected') === false) {
            $this->markTestSkipped('Compilation problem : "'.trim($res).'".');
        }

        $Php = new Phpexec($phpversion, $config->{'php'.str_replace('.', '', $config->phpversion)});
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
        $source = 'source/'.$file.'.php';

        if (is_dir($source)) {
            $shell = 'cd ../..; php exakat test -r -d ./tests/analyzer/'.$source.' -P '.$analyzer.' -p test -q -o -json';
        } else {
            $shell = 'cd ../..; php exakat test    -f ./tests/analyzer/'.$source.' -P '.$analyzer.' -p test -q -o -json';
        }

        $shell_res = shell_exec($shell);
        $res = json_decode($shell_res);
        if ($res === null) {
            $this->assertTrue(false, "Json couldn't be decoded : '$shell_res'\n$shell");
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