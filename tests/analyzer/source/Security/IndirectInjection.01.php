<?php 

/*
$a1 = $_GET['a1']; 
f($a1); 
function f($a1a) { 
    exec($a1a);
}
Can't do that yet (@todo)
*/

$a2 = $_POST;
shell_exec('a'.$a2);

$b2 = $_POST;
safeFunction($b2);

$a3 = $_COOKIE['a'];
shell_exec('a'.$a3);

$a4 = $_REQUEST['a']['b'];
shell_exec('a'.$a4);

$a5 = $_GET['a']['b'];
$b = "c$a5";

$a6 = $_GET['a']['b'];
$b = "c".$a6;

$a7 = $_GET;
foreach($a7 as $b => $c) {}

?>