<?php

namespace Test;

use Exakat\Phpexec;
use Exakat\Analyzer\Themes;
use PHPUnit\Framework\TestCase;
use AutoloadExt;

if (file_exists(__DIR__.'/../config.php')) {
    include __DIR__.'/../config.php';

    if (!isset($EXAKAT_PATH)) {
        die('Please, create a config.php file with a $EXAKAT_PATH variable to locate Exakat installation. '.PHP_EOL);
    }
    
    if (!file_exists($EXAKAT_PATH)) {
        die('Please, create a config.php file with a $EXAKAT_PATH variable to locate an existing Exakat installation. '.PHP_EOL);
    }
    
    if (!file_exists("$EXAKAT_PATH/exakat")) {
        die('Please, create a config.php file with a $EXAKAT_PATH variable to locate a valid Exakat installation. '.PHP_EOL);
    }
    
    $EXAKAT_PATH = realpath($EXAKAT_PATH);
} elseif (file_exists(__DIR__.'/../../../library/Exakat/Exakat.php')) {
    $EXAKAT_PATH = realpath(__DIR__.'/../../../');
} else {
    die("Run the tests from tests/analyzer/ or create a config.php file, with $EXAKAT_PATH leading to the root of a valid exakat installation.\n");
}

include_once "$EXAKAT_PATH/library/Autoload.php";
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');


class Analyzer extends TestCase {
    public function generic_test($file) {
        global $EXAKAT_PATH;
        $TEST_PATH = '.';
        
        if (preg_match('/^\w+_/', $file)) {
            $file = preg_replace('/^([^_]+?)_(.*)$/', '$1/$2', $file);
        }
        list($analyzer, $number) = explode('.', $file);
                
        // Test are run with test project.
        $ini = parse_ini_file("$EXAKAT_PATH/projects/test/config.ini");
        $phpversion = empty($ini['phpversion']) ? phpversion() : $ini['phpversion'];
        $test_config = preg_replace('/^([^_]+?)_(.*)$/', '$1/$2', substr(get_class($this), 5));
        $test_config = str_replace('\\', '/', $test_config);

        // initialize Config (needed by phpexec)
        $pwd = getcwd();
        chdir($EXAKAT_PATH);
        $config = new \Exakat\Config(array('foo', 'test', '-p', 'test'));
        chdir($pwd);

        $themes = new Themes("$EXAKAT_PATH/data/analyzers.sqlite", 
                             new AutoloadExt('')
                            );

        $analyzerobject = $themes->getInstance($test_config, null, $config);
        if ($analyzerobject === null) {
            $this->markTestSkipped("Couldn\'t get an analyzer for $test_config.");
        }
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        require("exp/$file.php");
        
        $versionPHP = 'php'.str_replace('.', '', $phpversion);
        $res = shell_exec("{$config->$versionPHP} -l $TEST_PATH/source/$file.php 2>/dev/null");
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
            
            $this->markTestSkipped("Needs configuration : $confs.");
        }
        
        $analyzer = escapeshellarg($test_config);
        $source = "source/$file.php";

        if (is_dir($source)) {
            $shell = "cd $EXAKAT_PATH/; php exakat test -r -d $TEST_PATH/tests/analyzer/$source -P $analyzer -p test -q -o -json";
        } else {
            $shell = "cd $EXAKAT_PATH/; php exakat test    -f $TEST_PATH/tests/analyzer/$source -P $analyzer -p test -q -o -json";
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


class AutoloadRemoteTest {
    public static function autoload($name) {
        $file = __DIR__.'/'.str_replace('\\', DIRECTORY_SEPARATOR, $name).'.php';

        if (file_exists($file)) {
            include $file;
        }
    }

    public function registerAutoload() {
        spl_autoload_register(array($this, 'autoload'));
    }
}

?>