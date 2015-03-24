<?php

// written cookie
$_COOKIE = array();
$_POST = array();
$_GET = array();

// read cookie
$x = $_GET['read1'];
$x2 = $_GET['read21']['read22'];
$x2 = $_GET['read31']['read32']['read33'];
$x = $_GET['read4']++;
join(',', $_GET);

$x = $_POST['read1'];
$x2 = $_POST['read21']['read22'];
$x2 = $_POST['read31']['read32']['read33'];
$x = $_POST['read4']++;
join(',', $_POST);

$x = $_REQUEST['read1'];
$x2 = $_REQUEST['read21']['read22'];
$x2 = $_REQUEST['read31']['read32']['read33'];
$x = $_REQUEST['read4']++;
join(',', $_REQUEST);

function process_gpr() {
    $x = $_GET['inside_process_gpr'];
    $x = $_POST['inside_process_gpr'];
    $x = $_REQUEST['inside_process_gpr'];
}

?>
