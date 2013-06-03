#!/usr/bin/env php
<?php
use Everyman\Neo4j\Client,
	Everyman\Neo4j\Index\NodeIndex,
	Everyman\Neo4j\Relationship,
	Everyman\Neo4j\Node,
	Everyman\Neo4j\Gremlin
	;

require_once 'example_bootstrap.php';

$client = new Client();
$dot = '';

$queryTemplate = "g.V.except([g.v(0)])";
$params = array('type' => 'IN');
$query = new Gremlin\Query($client, $queryTemplate, $params);
$vertices = $query->getResultSet();

$V = array();
foreach($vertices as $v) {
    $V[$v[0]->getId()] =  $v[0]->getProperty('code');
    
    if ($v[0]->getProperty('root')) {
        $root = $v[0]->getId();
    }
}

$queryTemplate = "g.E";
$params = array('type' => 'IN');
$query = new Gremlin\Query($client, $queryTemplate, $params);
$edges = $query->getResultSet();

$E = array();
foreach($edges as $e) {
    $id = $e[0]->getStartNode()->getId();
    
    if (!isset($E[$id])) {
        $E[$id] = array();
    }
    $E[$id][$e[0]->getEndNode()->getId()] = $e[0]->getType();
}

if (!isset($root)) {
    print "No root! Check the tree in Neo4j\n Aborting\n";die();
}

$text = display($V, $E, $root);

function display($V, $E, $root, $level = 0) {
    $r = '';
    
    $r .= str_repeat('  ', $level).$V[$root]."\n";
    if (isset($E[$root])) {
        foreach($E[$root] as $id => $label) {
            $r .= str_repeat('  ', $level)."Label : $label\n".display($V, $E, $id, $level + 1);
        }
    }
    
    return $r;
}

$args = $argv;
$id = array_search('-o', $args);
if ($id) {
    $filename = $args[$id + 1];
    $id = array_search('-f', $args);
    if (file_exists($filename) && !$id) {
        print "'$filename' exists\n Aborting\n";
    }
    
    $fp = fopen($filename, 'w+');
    fwrite($fp, $text);
    fclose($fp);
} else {
    print $text;
}

?>