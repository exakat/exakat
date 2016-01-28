<?php

$date = time();
$php = <<<PHP
<?php 

// Generated at $date
\$a = 1;
\$b = \$b + strtolower(\$c); 

PHP;

$url = 'http://localhost:7447/onepage/?script='.urlencode($php);
$res = file_get_contents($url);
$x = json_decode($res);

$y = new Stdclass();
$y->status = 'Instantiated';

while (isset($y->status)) {
    $url = 'http://localhost:7447/onepage/?id='.$x->id;
    $res = file_get_contents($url);
    $y = json_decode($res);
    print_r($y);
    sleep(2);
}


