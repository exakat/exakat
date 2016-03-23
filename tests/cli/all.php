<?php

$scripts = glob('./*.sh');

foreach($scripts as $script) {
    $name = substr($script, 2, -3);
    
    print "$name\n";
    
    $b = microtime(true);
    file_put_contents('log/'.$name.'.log', "Begin : $b\n");
    exec('sh '.$script.' >> log/'.$name.'.log');
    $e = microtime(true);

    $fp = fopen('log/'.$name.'.log', 'a');
    fwrite($fp, 'Duration : '.number_format(($e - $b) * 1000, 2)."ms \nEnd : $e\Date : ".date('r', $e)."\n");
    fclose($fp);
}

?>