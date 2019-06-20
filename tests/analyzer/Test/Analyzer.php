<?php

namespace Test;

use Exakat\Phpexec;
use Exakat\Analyzer\Themes;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;
use Exakat\Autoload\AutoloadExt;
use Exakat\Autoload\AutoloadDev;

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

include "$EXAKAT_PATH/library/Exakat/Autoload/Autoload.php";
include "$EXAKAT_PATH/library/Exakat/Autoload/AutoloadExt.php";
include "$EXAKAT_PATH/library/Exakat/Autoload/AutoloadDev.php";
include "$EXAKAT_PATH/library/helpers.php";

spl_autoload_register('\Exakat\Autoload\Autoload::autoload_test');
spl_autoload_register('\Exakat\Autoload\Autoload::autoload_phpunit');
spl_autoload_register('\Exakat\Autoload\Autoload::autoload_library');

abstract class Analyzer extends TestCase {
    public function generic_test($file) {
        global $EXAKAT_PATH;

        $test_path = dirname(__DIR__);

        if (preg_match('/^\w+_/', $file)) {
            $file = preg_replace('/^([^_]+?)_(.*)$/', '$1/$2', $file);
        }
        list($analyzer, $number) = explode('.', $file);
                
        // Test are run with test project.
        $ini = parse_ini_file("$EXAKAT_PATH/projects/test/config.ini");
        $phpversion = empty($ini['phpversion']) ? PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION : $ini['phpversion'];
        $phpversion = $phpversion === 'PHP' ? PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION : $phpversion;
        
        $test_config = preg_replace('/^([^_]+?)_(.*)$/', '$1/$2', substr(get_class($this), 5));
        $test_config = str_replace('\\', '/', $test_config);

        // initialize Config (needed by phpexec)
        $pwd = getcwd();
        chdir($EXAKAT_PATH);
        $config = new \Exakat\Config(array('foo', 'test', '-p', 'test'));
        chdir($pwd);
        
        $themes = new Themes("$EXAKAT_PATH/data/analyzers.sqlite", 
                             new AutoloadExt(''),
                             new AutoloadDev('')
                            );

        $analyzerobject = $themes->getInstance($test_config, null, $config);
        if ($analyzerobject === null) {
            $this->markTestSkipped("Couldn\'t get an analyzer for $test_config.");
        }
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        require("$test_path/exp/$file.php");
        
        $versionPHP = 'php'.str_replace('.', '', $phpversion);
        if (empty($config->$versionPHP)) {
            $versionPHP = 'php'.PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
        }

        if (preg_match('/^[^\/]+\/[^:]+:.+$/', $config->$versionPHP)) {
            $res = shell_exec("docker run -it --rm --name php4exakat -v \"$test_path\":/exakat  -w /exakat {$config->$versionPHP} php -l ./source/$file.php 2>/dev/null");
        } else {
            $res = shell_exec("{$config->$versionPHP} -l $test_path/source/$file.php 2>/dev/null");
        }
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

        if (is_dir("$test_path/source/$file.php")) {
            $shell = "cd $EXAKAT_PATH/; php exakat test -r -d ./tests/analyzer/source/$file.php -P $analyzer -p test -q -o -json";
        } else {
            $shell = "cd $EXAKAT_PATH/; php exakat test    -f ./tests/analyzer/source/$file.php -P $analyzer -p test -q -o -json";
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

function testSuiteBuilder(array $tests) {
    $suite = new TestSuite('PHPUnit Framework');
 
    foreach($tests as $i => $test) {
        $name  = str_replace(array(__DIR__, '/Test/', '\\', '.php',), array('', '', '/', '', ), $test);
        $name_ = str_replace(array(__DIR__.'/', '/Test/', '/', '.php',), array('', '', '_', '', ), $test);
    
        // check code
        $code = file_get_contents(__DIR__."/$name.php");
        preg_match_all('#test'.$name_.'(\d\d)#', $code, $r);
        $methods = $r[1];

        $sources = glob(dirname(__DIR__)."/source/$name.*.php");
        foreach($sources as &$v) {
            $v = preg_replace('#'.dirname(__DIR__).'/source/'.$name.'\.(\d+)\.php#is', '\1', $v);
        }
        unset($v);

        $exp = glob(dirname(__DIR__).'/exp/'.$name.'.*.php');
        foreach($exp as &$v) {
            $v = preg_replace('#'.dirname(__DIR__).'/exp/'.$name.'\.(\d+)\.php#is', '\1', $v);
        }

        $diff = array_diff($sources, $methods);
        if ($diff) {
            $out = array("missing ".count($diff)." test methods in Test/$name.php\n");
            foreach($diff as $d) {
                $out []= "    public function test$name$d()  { \$this->generic_test('$name.$d'); }\n";
            }
            $out []= "\n";
            
            print implode('', $out);
        }
        
        $diff = array_diff($exp, $methods);
        if ($diff) {
            echo "Missing ".count($diff)." methods for tests in Test/$name.php\n",
                 "   php prepareexp.php $name\n\n";
        }

        $diff = array_diff($methods, $exp);
        if ($diff) {
            echo "Missing ".count($diff)." exp for tests in Test/$name.php\n",
                 implode(', ', $diff)."\n\n";
        }

        $diff = array_diff($methods, $sources);
        if ($diff) {
            echo "missing ".count($diff)." sources for tests in Test/$name.php\n",
                 implode(', ', $diff)."\n\n";
        }
    
        $d = basename($test, '.php');
        $c = basename(dirname($test));
        $testClass = "\\Test\\$c\\$d";
    
        $suite->addTestSuite($testClass);
    }
    
    return $suite;
}

?>