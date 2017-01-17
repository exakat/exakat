<?php

define('a\constant\in\another\space', 1);
define('\a\constant\in\another\space', 1);

define('\a\constant\in\another\namespace', 1); // This is wrong! 

define('\a\constant\in\unset\space', 1); // This is wrong! (unset)
define('\a\constant\in\unsetting\space', 1); // This is wrong!

define('\a\co$nstant\in\unset\space', 1); // This is wrong!

define('cons$tant', 1); // This is wrong!

//echo \a\constant\in\unset\space;
?>