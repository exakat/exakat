<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Random extends Tokenizer {
    /* 15 methods */
    public function testRandom01()  { $this->random_test(); }
    public function testRandom02()  { $this->random_test(); }
    public function testRandom03()  { $this->random_test(); }



    public function random_test()  { 
        $tests = glob('Test/*.php');
        if ($id = array_search('Test/Tokenizer.php', $tests)) {
            unset($tests[$id]);
        }
        if ($id = array_search('Test/Random.php', $tests)) {
            unset($tests[$id]);
        }
        
        $test = $tests[array_rand($tests, 1)];

        $code = file_get_contents($test);
        $max = preg_match_all("/'.*?\.\d\d'/is", $code, $r);
        
        $test = substr($test, 5, -4).'.'.substr("00".rand(1, $max), -2);
        $this->generic_test($test);
        
        return $test;
    }
}
?>