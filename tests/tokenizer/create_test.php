<?php
    $args = $argv;
    
    if (!isset($args[1])) {
        print "Usage : create_test.php Testname\n ";
        die();
    }
    $test = $args[1];
    
    if ($test != ucfirst(strtolower($test))) {
        print "Usage : create_test.php Testname (case is important)\n ";
    }
    
    $files = glob('source/'.$test.'.*.php');
    sort($files);
    $last = array_pop($files);
    print $last;
    $number = intval(str_replace(array($test, '.php', '.', 'source/'), '', $last));
    
    if ($number + 1 == 100) { 
        print "Too many tests for $test : reaching 100 of them. Aborting\n";
        die();
    }
    $next = substr("00".($number + 1), -2);

    if (file_exists('Test/'.$test.'.php')) {
        $code = file_get_contents('Test/'.$test.'.php');
    } else {
        copy('Test/Skeleton.php', 'Test/'.$test.'.php');
        $code = file_get_contents('Test/'.$test.'.php');
        
        $code = str_replace('Skeleton', $test, $code);
    }
    
    $code = substr($code, 0, -4)."    public function test$test$next()  { \$this->generic_test('$test.$next'); }
".substr($code, -4);
    $count = $next + 0;
    $code = preg_replace('#/\* \d+ methods \*/#is', '/* '.$count.' methods */', $code);

    file_put_contents('Test/'.$test.'.php', $code);

    shell_exec('bbedit ./source/'.$test.'.'.$next.'.php');
?>