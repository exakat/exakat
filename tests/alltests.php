<?php
/*
   +----------------------------------------------------------------------+
   | Cornac, PHP code inventory                                           |
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

include_once(dirname(__DIR__).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Framework_AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('PHPUnit Framework');
 
        $tests = array( 
            '\\Test\\Addition',
            '\\Test\\Multiplication',
            '\\Test\\Assignation',
        );
        
        foreach($tests as $i => $test ) {
            $suite->addTestSuite($test);
            
            continue;
        }
/*
            $fichier = $test;
            if (!file_exists('class/'.$fichier)) {
                unset($tests[$i]); 
                print "Tests $test can't be found (no file $fichier) : omitted\n";
                continue;
            }
            
            $code = file_get_contents(dirname(__FILE__)."/class/".$fichier);
            if (!preg_match('$class (.*?_Test) $', $code, $r)) {
                print "Couldn't find test class in '$fichier'\n";
                die();
            }
            
            include('class/'.$test);
            $class = $r[1];
            $methods = get_class_methods($class);
            $methods = preg_grep('$^test$', $methods);
        
            preg_match('$test(.*)(\d+)$', $methods[0], $r);
            $nom = strtolower($r[1]);
            
            foreach($methods as $id => $method) {
                $methods[$id] = preg_replace('$\D+$', '', $method);
            }
            
            $lestests = glob('scripts/'.$nom.'.*');
            
            foreach($lestests as $id => $test) {
                $script = preg_replace('$\D+$', '', $test);
                
                if (!in_array($script, $methods)) {
                    print "There is a test method missing for script $script with name $nom\n";
                }
            }
        
            $lestests = glob('exp/'.$nom.'.*');
            
            foreach($lestests as $id => $test) {
                $script = preg_replace('$\D+$', '', $test);
                
                if (!in_array($script, $methods)) {
                    print "The result file is missing for $script with name $nom\n";
                }
            }
        }
          
         foreach($tests as $test) {
             $test = substr($test, 6); // exit le class.
             $test = substr($test, 0, -4); // exist le .php
             $test = str_replace('.','_', $test); // exit le .
             $test = ucwords($test);
             $test = str_replace('_test','_Test', $test);

            $suite->addTestSuite($test);
         }
*/ 
        return $suite;
    }
}
?>