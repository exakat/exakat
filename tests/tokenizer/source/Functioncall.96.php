<?php

// Normal case
echo 1, 2, 3, 3;

// Echo is inside
[[1,2,function ($x) {echo 3, 3;}] ,[4,5,6],[7,8,9]];


$a = [
		[$a, function ($s) {
			echo "d", print_r($g, true), PHP_EOL;
			die();
		}],
		[$chn2, function ($msg) {
			echo "e", print_r($f, true), PHP_EOL;
		}],
	];