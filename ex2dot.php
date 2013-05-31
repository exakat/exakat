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

foreach($vertices as $v) {
    $dot .= $v[0]->getId()." [label=\"".$v[0]->getProperty('code')."\"];\n";
}

$queryTemplate = "g.E";
$params = array('type' => 'IN');
$query = new Gremlin\Query($client, $queryTemplate, $params);
$edges = $query->getResultSet();

foreach($edges as $e) {
    $dot .= "".$e[0]->getStartNode()->getId()." -> ".$e[0]->getEndNode()->getId()." [label=\"".$e[0]->getType()."\"];\n";
}


$fp = fopen('export.dot','w+');
$dot = " digraph graphname {
$dot
 }";
fwrite($fp, $dot);
fclose($fp);

?>
