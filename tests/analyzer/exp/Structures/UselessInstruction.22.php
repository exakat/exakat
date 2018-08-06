<?php

$expected     = array('return $c = preg_replace_callback(\'/a/\', function ($matches) use ($styleMapping) { /**/ } , $content)',
                     );

$expected_not = array('return preg_replace_callback(\'/a/\', function ($matches) use ($styleMapping) { /**/ } , $content)',
                     );

?>