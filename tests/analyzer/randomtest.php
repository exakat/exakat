<?php

use PHPUnit\Framework\TestSuite;

include_once(dirname(__DIR__, 2).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Framework_Randomtest extends TestSuite {

    public static function suite() {
        $suite = new TestSuite('PHPUnit Framework');
 
        $tests = glob('Test/*/*.php');
        foreach($tests as $id => $t) {
           $tests[$id] = '\\'.str_replace(array('/','.php'), array('\\',''), $t);
        }
        
        shuffle($tests);
        $tests = array_slice($tests, 0, 20);
        
        print "Testing with ".count($tests)." tests\n";
        $total = 0;
        foreach($tests as $test) {
            $testFile = str_replace('\\', '/', str_replace('\Test\\', 'Test/', $test)).'.php';
            preg_match('/(\d) methods/', file_get_contents($testFile), $r);
            print substr("phpunit ./".$testFile." ".str_repeat(' ', 60), 0, 60)."($r[1] - $total)\n";
            $total += $r[1];
        }
        print "Testing a total of $total tests\n";
        
        $offset = 0;
        $number = 1000;
        foreach($tests as $i => $test ) {
            if ($i < $offset) { 
                continue; 
            }
            $name = str_replace('\\', '/', str_replace('\\Test\\', '', $test));
            $name_ = str_replace('\\', '_', str_replace('\\Test\\', '', $test));

            // check code
            $code = file_get_contents('Test/'.$name.'.php');
            preg_match_all('#test'.$name_.'\d\d#', $code, $r);
            $methods = $r[0];
            foreach($methods as &$v) {
                $v = preg_replace('#test'.$name_.'(\d+)#is', '\1', $v);
            }

            $sources = glob('source/'.$name.'.*.php');
            foreach($sources as &$v) {
                $v = preg_replace('#source/'.$name.'\.(\d+)\.php#is', '\1', $v);
            }

            $exp = glob('exp/'.$name.'.*.php');
            foreach($exp as &$v) {
                $v = preg_replace('#exp/'.$name.'\.(\d+)\.php#is', '\1', $v);
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

            list($a, $b, $c, $d) = explode('\\', $test);
            $testClass = "\\Test\\$c\\$d";

            $suite->addTestSuite($testClass);
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