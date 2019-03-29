<?php

usort($a,function($a,$b){				
	return strcmp(strip_tags($a),strip_tags($b));
});

usort($a,function($A,$B){				
	return strcmp($A,$B);
});

    $r = preg_replace_callback('/([a-z0-9])([A-Z])/', function($a1) {
      return $matches[1] . ' ' . strtolower($a1[2]);
    }, $r);

    $r = preg_replace_callback('/([a-z0-9])([A-Z])/', function($A1) {
      return strtolower($A1);
    }, $r);
    $r = preg_replace_callback('/([a-z0-9])([A-Z])/', function($A2) {
      return strtolower($A2[2]);
    }, $r);

?>