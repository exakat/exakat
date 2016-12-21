<?php

$a = array(0xB7 => 1, 
      0xB8 => 1,
      0xB7 => 1, 
      );
var_dump($a);
$a = array(0xB7 => 1, 
      0xB8 => 1,
      0xB9 => 1, 
      );
var_dump($a);

$a = array('0xB7' => 1, 
      '0xB8' => 1, 
      '0xB8' => 1);
var_dump($a);
$a = array('0xB7' => 1, 
      '0xB8' => 1, 
      '0xB9' => 1);
var_dump($a);
?>