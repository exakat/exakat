<?php

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Framework_AllTests extends PHPUnit_Framework_TestSuite {

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
 
        $tests = glob('Test/*.php');
        foreach($tests as $id => $t) {
            if ($t == 'Test/Skeleton.php') { 
                unset($tests[$id]) ;
            } elseif ($t == 'Test/Tokenizeur.php') { 
                unset($tests[$id]) ;
            } elseif ($t == 'Test/Random.php') { 
                unset($tests[$id]) ;
            } else {
                $tests[$id] = '\\'.str_replace(array('/','.php'), array('\\',''), $t);
            }
        }
        
        foreach($tests as $i => $test ) {
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

            $exp = glob('exp/'.$name.'.*.txt');
            foreach($exp as $k => $v) {
                $exp[$k] = preg_replace('#exp/'.$name.'\.(\d+)\.txt#is', '\1', $v);
            }
            
            $diff = array_diff($sources, $methods);
            if ($diff) {
                print "missing ".count($diff)." test methods in Test/$name.php \n";
                foreach($diff as $d) {
                    print "    public function test$name$d()  { \$this->generic_test('$name.$d'); }\n";
                }
                print "\n";
            }

            $diff = array_diff($methods, $exp);
            if ($diff) {
                print "missing ".count($diff)." results for tests in Test/$name.php (".join(' ', $diff).")\n";
                print "   php prepareexp.php $name\n";
                print "\n";
            }

            $suite->addTestSuite($test);
            
            continue;
        }
        return $suite;
    }
}
?>