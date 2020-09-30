<?php

namespace Test;

use Exakat\Phpexec;
use Exakat\Dump\Dump;
use Exakat\Analyzer\Rulesets;
use Exakat\Analyzer\Dump\AnalyzerHashAnalyzer;
use Exakat\Analyzer\Dump\AnalyzerTable;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\TestSuite;
use Exakat\Autoload\AutoloadExt;
use Exakat\Autoload\AutoloadDev;
use Exakat\Graph\Graph;

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
include "$EXAKAT_PATH/vendor/autoload.php";
include "$EXAKAT_PATH/library/helpers.php";

$autoload = new \Exakat\Autoload\Autoload();
$autoload->registerAutoload();
spl_autoload_register(array($autoload, 'autoload_test'));
spl_autoload_register(array($autoload, 'autoload_phpunit'));

abstract class Analyzer extends TestCase {
    public function generic_test(string $file) {
        global $EXAKAT_PATH;

        $test_path = dirname(__DIR__);
        copy(__DIR__.'/../config/unitTest.json', __DIR__.'/../../../stubs/unitTest.json');

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

        // collect the expected values
        require("$test_path/exp/$file.php");

        $rulesets = new Rulesets("$EXAKAT_PATH/data/analyzers.sqlite", 
                               new AutoloadExt(''),
                            );

        $analyzerobject = $rulesets->getInstance($test_config);
        
        if ($analyzerobject === null) {
            $this->markTestSkipped("Couldn\'t get an analyzer for $test_config.");
        }
        if (!$analyzerobject->checkPhpVersion($phpversion)) {
            $this->markTestSkipped('Needs version '.$analyzerobject->getPhpVersion().'.');
        }

        $config = exakat('config');
//        $datastore = exakat('datastore');
//        $datastore->create();
//        $data = $datastore->getCol('TokenCounts', 'token');

        $versionPHP = 'php'.str_replace('.', '', $phpversion);
        if (empty($config->$versionPHP)) {
            die("Warning : No binary for $phpversion\n");
        }

        if (preg_match('/^[^\/]+\/[^:]+:.+$/', $config->$versionPHP)) {
            $res = shell_exec("docker run -it --rm --name php4exakat -v \"$test_path\":/exakat  -w /exakat {$config->$versionPHP} php -l ./source/$file.php 2>/dev/null");
        } else {
            $res = shell_exec("{$config->$versionPHP} -l $test_path/source/$file.php 2>/dev/null");
        }
        
        if (strpos($res, 'No syntax errors detected') === false) {
            $this->markTestSkipped('Compilation problem : "'.trim($res).'".');
        }
/*
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
*/
        $analyzer = escapeshellarg($test_config);

        if (is_dir("$test_path/source/$file.php")) {
            $shell = "cd $EXAKAT_PATH/; php exakat test -r -d ./tests/analyzer/source/$file.php -P $analyzer -p test -q -o -json";
        } else {
            $shell = "cd $EXAKAT_PATH/; php exakat test    -f ./tests/analyzer/source/$file.php -P $analyzer -p test -q -o -json";
        }

        $shell_res = shell_exec($shell);

        if (isset($fetch_query)) {
            $gremlin = Graph::getConnexion();
            $gremlin->init();

            $res = $gremlin->query($fetch_query)->toArray();
//            $res = array();
        } else {
            $res = json_decode($shell_res, true);
            if ($res === null) {
                $this->assertTrue(false, "Json couldn't be decoded : '$shell_res'\n$shell");
            }
        }

        $this->file     = $file;
        $this->number   = $number;
        $this->analyzer = $analyzer;

        if ($analyzerobject instanceof AnalyzerHashAnalyzer) {
            $this->checkTestOnHashAnalyzer($res, $expected, $expected_not, $analyzerobject);
        } elseif ($analyzerobject instanceof AnalyzerTable) {
            $this->checkTestOnFullarray($res, $expected, $expected_not);
        } elseif (isset($res[0]) && !is_array($res[0])) {
            $this->checkTestOnFullcode($res, $expected, $expected_not);
        } elseif (isset($res[0]['fullcode'])) {
            $list = array_column($res, 'fullcode');
            $this->checkTestOnFullcode($list, $expected, $expected_not);
        } elseif (isset($res[0]['key'], $res[0]['value'])) {
            $this->checkTestOnHash($res, $expected, $expected_not);
        } elseif (empty($res)) {
            $this->checkTestOnFullcode(array(), $expected, $expected_not);
        } elseif (isset($res[0]['built'])) {
            $this->checkTestOnFullarray($res, $expected, $expected_not);
        } elseif (isset($res[0]['including'])) {
            $this->checkTestOnFullarray($res, $expected, $expected_not);
        } elseif ($analyzerobject instanceof AnalyzerTable) {
            $this->checkTestOnFullarray($res, $expected, $expected_not);
        } else {
            print "How shall we test this?\n";
            print_r($res);
        }
        
        unlink(__DIR__.'/../../../stubs/unitTest.json');
    }

    private function checkTestOnHashAnalyzer(array $list = array(), array $expected = array(), array $expectedNot = array(), AnalyzerHashAnalyzer $analyzerobject) : void {
        global $EXAKAT_PATH;
        
        $dump = Dump::factory("$EXAKAT_PATH/projects/test/dump.sqlite", Dump::READ);
        $res = $analyzerobject->getResults($dump)->toHash('key', 'value');

        foreach($expected as $key => $value) {
            if (isset($res[$key]) && $res[$key] == $value) {
                unset($expected[$key]);
                unset($res[$key]);
            }
        }

        $missed = array();
        foreach($expected as $key => $value) {
            $missed[] = "$key => $value";
        }
        $this->assertEmpty($missed, count($missed)." expected values were not found :\n '".join("',\n '", $missed)."'\n");

        $missed = array();
        foreach($expectedNot as $key => $value) {
            if (isset($res[$key]) && $res[$key] == $value) {
                unset($res[$key]);
                $missed[] = "$key => $value";
            }
        }
        $this->assertEmpty($missed, count($missed)." not expected values were still found :\n '".join("',\n '", $missed)."'\n");

        $missed = array();
        foreach($res as $key => $value) {
            $missed[] = "$key => $value";
        }
        $this->assertEmpty($missed, count($res)." too many values were found :\n ".join(",\n ", $missed).",\n");
    }
    
    private function checkTestOnFullarray(array $list = array(), array $expected = array(), array $expectedNot = array()) : void {
        $list  = array_map(function(array $x) : array  { unset($x['id']); return $x;}, $list);
        $list2 = array_map(function(array $x) : string { return crc32(json_encode($x));}, $list);

        $expected2     = array_map(function($x) : string { return crc32(json_encode($x));}, $expected);
        $expectedNot2  = array_map(function($x) : string { return crc32(json_encode($x));}, $expectedNot);

        $display = array();
        $missed = array_diff($expected2, $list2);
        if(!empty($missed)) {
            foreach($missed as $key => $value) {
                $display[] = $expected2[$key];
            }
        }
        $this->assertEmpty($missed, count($missed)." expected values were not found :\n '".join("',\n '", $missed)."'\n\nin the ".count($display)." received values of \n".print_r($display, true));

        $list2 = array_diff($list2, $expected2);
        $not   = array_intersect($expectedNot2, $list2);

        if(!empty($not)) {
            foreach($not as $key => $value) {
                $display[] = $list[$key];
            }
        }
        $this->assertEmpty($not, count($not)." values that are not expected, were found :\n '".join("',\n '", $missed)."'\n\nin the ".count($display)." received values of \n".print_r($display, true));
        
        $extra = array_diff($list2, $expectedNot2);
        if(!empty($extra)) {
            foreach($extra as $key => $value) {
                $display[] = $list[$key];
            }
        }
        $this->assertEmpty($extra, count($extra)." extra values were found :\n '".join("',\n '", $missed)."'\n\nin the ".count($list)." received values of \n".print_r($display, true));
        
    }
    
    private function checkTestOnFullcode(array $list = array(), array $expected = array(), array $expectedNot = array()) : void {
        if (isset($expected) && is_array($expected)) {
            $missing = array();
            foreach($expected as $e) {
                if (($id = array_search($e, $list)) !== false) {
                    unset($list[$id]);
                } else {
                    $missing[] = $e;
                }
            }
            $list = array_map(function (string $x) : string { return str_replace("'", "\\'", $x); }, $list);
            $this->assertEquals(count($missing), 0, count($missing)." expected values were not found :\n '".join("',\n '", $missing)."'\n\nin the ".count($list)." received values of \n '".join("', \n '", $list)."'

source/$this->file.php
exp/$this->file.php
phpunit --filter=$this->number Test/$this->analyzer.php

");
            // also add a phpunit --filter to rerun it easily
        }
        
        if (isset($expectedNot) && is_array($expected)) {
            $extra = array();
            foreach($expectedNot as $e) {
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

    private function checkTestOnHash(array $list = array(), array $expected = array(), array $expectedNot = array()) : void {
        $expected     = array_column($expected, 'value', 'key');
        $expectedNot  = array_column($expectedNot, 'value', 'key');
        $list         = array_column($list, 'value', 'key');

        if (isset($expected) && is_array($expected)) {
            $missing = array();
            foreach($expected as $k => $v) {
                if (isset($list[$k]) && $list[$k] == $v) {
                    unset($list[$k]);
                } else {
                    $missing[] = $k.' ('.$v.')';
                }
            }

            $this->assertEmpty($missing, count($missing)." expected values were not found :\n '".join("',\n '", $missing)."'\n\nin the ".count($list)." received values of \n '".join("', \n '", $list)."'

source/$this->file.php
exp/$this->file.php
phpunit --filter=$this->number Test/$this->analyzer.php

");
            // also add a phpunit --filter to rerun it easily
        }

        if (isset($expectedNot) && is_array($expected)) {
            $extra = array();
            foreach($expectedNot as $k => $v) {
                if (isset($list[$k]) && $list[$k] == $v) {
                    $extra[] = $k;
                    unset($list[$k]);
                } 
            }
            // the not expected
            $this->assertEquals(count($extra), 0, count($extra)." values were found and shouldn't be : \n".join(', ', $extra)."");
        }

        // the remainings
        $display = array();
        foreach($list as $key => $value) {
            $display[] = "                      array('key'    => '$key',
                            'value' => '$value',
                           ),
";
        }
        $this->assertEquals(count($list), 0, count($list)." values were found and are unprocessed : \n".join(PHP_EOL, $display));
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