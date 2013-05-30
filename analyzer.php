#!/usr/bin/env php
<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node;

require_once 'example_bootstrap.php';

$php = file_get_contents('tests/test006.php');
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

    print_r($token);
}

$classes = array(
    'Variable', 'Integer',
    'Assignation',
    'Addition', 'Multiplication',
    'Phpcode',
                );

include('classes/'.'Token'.'.php');
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
while($prev > $count) {
    $prev = $count; 
    foreach($regex as $r) {
        $r->check();
    }

    if ($count = Token::countLeftToken()) {
        print "Remains $count of $total tokens to process! \n";
    } else {
        print "All $total tokens have been processed! \n";
        $prev = $count = 0;
    }
}
die();

//$actors = new NodeIndex($client, 'actors');
//	$keanu->relateTo($matrix, 'IN')->save();

/*
// Initialize the data
if ($cmd == 'init') {
//	
	$laurence = $client->makeNode()->setProperty('name', 'Laurence Fishburne')->save();
	$jennifer = $client->makeNode()->setProperty('name', 'Jennifer Connelly')->save();
	$kevin = $client->makeNode()->setProperty('name', 'Kevin Bacon')->save();

	$actors->add($keanu, 'name', $keanu->getProperty('name'));
	$actors->add($laurence, 'name', $laurence->getProperty('name'));
	$actors->add($jennifer, 'name', $jennifer->getProperty('name'));
	$actors->add($kevin, 'name', $kevin->getProperty('name'));

	$matrix = $client->makeNode()->setProperty('title', 'The Matrix')->save();
	$higherLearning = $client->makeNode()->setProperty('title', 'Higher Learning')->save();
	$mysticRiver = $client->makeNode()->setProperty('title', 'Mystic River')->save();

	$laurence->relateTo($matrix, 'IN')->save();

	$laurence->relateTo($higherLearning, 'IN')->save();
	$jennifer->relateTo($higherLearning, 'IN')->save();

	$laurence->relateTo($mysticRiver, 'IN')->save();
	$kevin->relateTo($mysticRiver, 'IN')->save();

// Find a path
} else if ($cmd == 'path' && !empty($argv[2]) && !empty($argv[3])) {
	$from = $argv[2];
	$to = $argv[3];

	$fromNode = $actors->findOne('name', $from);
	if (!$fromNode) {
		echo "$from not found\n";
		exit(1);
	}

	$toNode = $actors->findOne('name', $to);
	if (!$toNode) {
		echo "$to not found\n";
		exit(1);
	}

	// Each degree is an actor and movie node
	$maxDegrees = 6;
	$depth = $maxDegrees * 2;

	$path = $fromNode->findPathsTo($toNode)
		->setmaxDepth($depth)
		->getSinglePath();

	if ($path) {
		foreach ($path as $i => $node) {
			if ($i % 2 == 0) {
				$degree = $i/2;
				echo str_repeat("\t", $degree);
				echo $degree . ': ' .$node->getProperty('name');
				if ($i+1 != count($path)) {
					echo " was in ";
				}
			} else {
				echo $node->getProperty('title') . " with\n";
			}
		}
		echo "\n";
	}
}

*/

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