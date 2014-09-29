<?php
/*
   +----------------------------------------------------------------------+
   | Exakat, PHP static code analysis                                     |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010 - 2011                                            |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Damien Seguy <damien.seguy@gmail.com>                        |
   +----------------------------------------------------------------------+
 */

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Framework_Randomtest extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
 
        $tests = glob('Test/*.php');
        foreach($tests as $id => $t) {
            if ($t == 'Test/Skeleton.php') { 
                unset($tests[$id]) ;
            } elseif ($t == 'Test/Analyzer.php') { 
                unset($tests[$id]) ;
            } elseif ($t == 'Test/Random.php') { 
                unset($tests[$id]) ;
            } else {
                $tests[$id] = '\\'.str_replace(array('/','.php'), array('\\',''), $t);
            }
        }
        
        shuffle($tests);
        $tests = array_slice($tests, 0, 20);
        
        print "Testing with ".count($tests)." tests\n";
        $total = 0;
        foreach($tests as $test) {
            preg_match('/(\d) methods/', file_get_contents(str_replace('\Test\\', 'Test/', $test).'.php'), $r);
            print "+ $test ($r[1])\n";
            $total += $r[1];
        }
        print "Testing a total of $total tests\n";
        
        $offset = 0;
        $number = 1000;
        foreach($tests as $i => $test ) {
            if ($i < $offset) continue;
            $name = str_replace('\\Test\\', '', $test);

            // check code
            $code = file_get_contents('Test/'.$name.'.php');
            preg_match_all('/test'.$name.'\d\d/', $code, $r);
            $methods = array();
            foreach($r[0] as $k => $v) {
                $methods[$k] = preg_replace('#test'.$name.'(\d+)#is', '\1', $v);
            }

            $sources = glob('source/'.$name.'.*.php');
            foreach($sources as $k => $v) {
                $sources[$k] = preg_replace('#source/'.$name.'\.(\d+)\.php#is', '\1', $v);
            }

            $exp = glob('exp/'.$name.'.*.php');
            foreach($exp as $k => $v) {
                $exp[$k] = preg_replace('#exp/'.$name.'\.(\d+)\.php#is', '\1', $v);
            }
            
            $diff = array_diff($sources, $methods);
            if ($diff) {
                print "missing ".count($diff)." test methods in Test/$name.php\n";
                foreach($diff as $d) {
                    print "    public function test$name$d()  { \$this->generic_test('$name.$d'); }\n";
                }
                print "\n";
            }
            
            $diff = array_diff($exp, $methods);
            if ($diff) {
                print "missing ".count($diff)." results for tests in Test/$name.php\n";
                print "   php prepareexp.php $name\n";
                print "\n";
            }

            $suite->addTestSuite($test);
            if ($i > $offset + $number) { 
                print "Limited at $number element from $offset position\n";
                return $suite; 
            }
            
            continue;
        }
        
        return $suite;
    }
}
?>