<?php
  $a =   function ($b) {  $e++;  } ;
  $a =   function ($b = 1) {  $e++;  } ;
  $a =   function  ($b, $c) {  $e++;  } ;
  $a =   function  ($b, $c = 2) {  $e++;  } ;
  $a =   function  ($b = 2, $c) {  $e++;  } ;
  $a =   function ($b, $c, $d = null) {  $e++;  } ;
  $a =   function  ($b, $c, $d = null, $e) {  $e++;  } ;
?>;