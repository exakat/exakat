<?php

$args = $argv;

if (count($args) < 2) {
    print "Usage : prepareexp.php Test \n Aborting\n";
    die();
}

$test = $argv[1];

$sources = glob('source/'.$test.'*');
if (count($sources) == 0) { print "No tests for '$test'.\nAborting\n"; } 

$max = preg_replace('#source/'.$test.'.(\d+)\.php#', '\1', max($sources)) + 1;
$max = substr( "00$max", -2);
$new = 'source/'.$test.'.'.$max.'.php';


$old = glob('scripts_old/'.strtolower($test).'.*.test.php');
if (count($old) == 0) { 
    $old = glob('scripts_old/'.strtolower(str_replace('_','', $test)).'.*.test.php');
} 

if (count($old) == 0) {
    die("No more scripts for '$test'\n");
}

natsort($old);
$ancien = array_shift($old);

$code = file_get_contents($ancien);
$code = str_replace('/*
   +----------------------------------------------------------------------+
   | Cornac, PHP code inventory                                           |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010 - 2011                                            |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Damien Seguy <damien.seguy@gmail.com>                        |
   +----------------------------------------------------------------------+
 */', '', $code);
 
file_put_contents($new, $code);
unlink($ancien);

$code = file_get_contents('Test/'.$test.'.php');
$code = substr($code, 0, -4)."    public function test$test$max()  { \$this->generic_test('$test.$max'); }
".substr($code, -4);
$count = $max + 0;
$code = preg_replace('#/\* \d+ methods \*/#is', '/* '.$count.' methods */', $code);

file_put_contents('Test/'.$test.'.php', $code);

print $ancien." moved to ".$new."\n";

?>