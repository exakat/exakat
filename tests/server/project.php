<?php

$x = new stdclass();
$x->project = 'a5f93967f';

if (in_array('-i', $argv)) {
    print "Intialization\n";

    shell_exec('php exakat remove -p '.$x->project);
    $url = 'http://localhost:7447/project/?vcs='.urlencode('https://github.com/sirprize/scrubble.git');
    $res = file_get_contents($url);
    $x = json_decode($res);
    print_r($res);
}

$y = new Stdclass();
$y->status = 'Instantiated';

while (isset($y->status)) {
    $url = 'http://localhost:7447/project/?project='.$x->project;
    $res = file_get_contents($url);
    $y = json_decode($res);
    print_r($y);
    sleep(2);
}


