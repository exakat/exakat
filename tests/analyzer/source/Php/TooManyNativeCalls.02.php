<?php

// 2 native calls
echo array_map(function ($x) {}, explode(',', $string));

// 3 native calls
echo array_map(function ($x) {}, explode(',', ucfirst($string)));

// 3 native calls
echo array_map(function ($x) { return strtoupper($x); }, explode(',', ucfirst($string)));

// 3 native calls
echo array_map(function ($x) { return strtoupper(substr($x, 0, 1)); }, explode(',', ucfirst($string)));

// 4 native calls
echo array_map(function ($x) {}, explode(',', ucfirst(substr($string, 0, 10))));

?>