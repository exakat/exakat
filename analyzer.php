#!/usr/bin/env php
<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node;

require_once 'example_bootstrap.php';

$php = file_get_contents('tests/test010.php');
$tokens = token_get_all($php);

$client = new Client();
$line = 0;

$TPHP = array(";" => 800,
              "=" => 801,
              "+" => 802,
              "-" => 803,
              "*" => 804,
              "/" => 805,
              "%" => 806,
              );

foreach($tokens as $id => $token) {
    if ($token[0] == T_WHITESPACE) { continue; }
    
    if (is_array($token)) {
        $T[$id] = $client->makeNode()->setProperty('token', token_name($token[0]))
                                     ->setProperty('code', $token[1])
                                     ->setProperty('line', $token[2])->save();
        $line = $token[2];
    } else {
        if (!isset($TPHP[$token])) {
            print "No TPHP for '{$token}'\n";
            $TPHP[$token] = max($TPHP) + 1;
            print $TPHP;
        }
        $T[$id] = $client->makeNode()->setProperty('token', $token)
                                     ->setProperty('code', $token)
                                     ->setProperty('line', $line)->save();
    }
    
    if (!isset($previous)) {
        $previous = $T[$id];
    } else {
        $previous->relateTo($T[$id], 'NEXT')->save();
        $previous = $T[$id];
    }

}

$classes = array(
    'Variable', 'Integer',
    'Assignation',
    'Multiplication','Addition', 
    'Phpcode',
                );

include('classes/'.'Token'.'.php');
include('classes/'.'TokenAuto'.'.php');
foreach($classes as $class) {
    include('classes/'.$class.'.php');
}

$regex = array();
foreach($classes as $class) {
    $regex[$class] = new $class($client);
}

$total = Token::countTotalToken();
$prev = Token::countLeftToken() + 1;
$count = $prev - 1; 
$round = 0;

while($prev > $count) {
    $round++;
    
    $prev = $count; 

    $r = array_pop($regex);
    $r->resetReserve();
    array_push($regex, $r);
    foreach($regex as $r) {
        $r->check();
        
        $r->reserve();
    }
    unset($precedence);

    if ($count = Token::countLeftToken()) {
        print "$round) Remains $count of $total tokens to process! \n";
    } else {
        print "$round) All $total tokens have been processed! \n";
        $prev = $count = 0;
    }
}

function array_flatten($array, $level = 1) {
    $r = array();
    
    foreach($array as $a) {
        if ($level > 1 && is_array($a)) {
            $a = array_flatten($a, $level - 1);
        }
        $r = array_merge($r, $a);
    }
    
    return $r;
}

?>