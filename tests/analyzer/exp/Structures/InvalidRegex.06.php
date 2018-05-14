<?php

$expected     = array('preg_replace_callback(\'/^(.*)\\\\\\\\([^\\\\]+)$/\', function ($r) { /**/ } , $a1)',
                      'preg_replace_callback(\'/^(.*)\\\\\\\\([^\\\\\\\\]/++)$/\', function ($r) { /**/ } , $a3)',
                      'preg_replace_callback("/^(.*)\\\\\\\\([^\\\\]+)$/", function ($r) { /**/ } , $a4)',
                      'preg_replace_callback("/^(.*)\\\\\\\\([^\\\\\\\\]/++)$/", function ($r) { /**/ } , $a6)',
                      'preg_match(\'/[^a-z0-9_\\\\]/i\', $a8)',
                      'preg_match("/[^a-z0-9_\\\\]/i", $a10)',
                     );

$expected_not = array('preg_replace_callback(\'/^(.*)\\\\([^\\]+)$/\', function ($r) { /**/ } , $a2)',
                      'preg_replace_callback("/^(.*)\\\\([^\\]+)$/", function ($r) { /**/ } , $a5)',
                     );

?>