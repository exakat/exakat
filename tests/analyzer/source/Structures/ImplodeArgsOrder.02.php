<?php

	implode(', ', $fullcode);
	implode(PHP_EOL, $theTable);
	implode($a, $theTable);
	
	const AR = [];
	implode(AR, $theTable);
	implode($theTable, AR);

function foo() {
        $return = array();

        $return = '.a{[' . implode(', ', $return) . ']}';
        return $return;
    }

function fooArg($return = array()) {
        $return = '.a{[' . implode(', ', $return) . ']}';
        return $return;
    }

function fooArg2($returnS = 'string') {
        $returnS = '.a{[' . implode(', ', $returnS) . ']}';
        return $return;
    }

function fooStatic() {
        static $returnStatic = array();

        $returnStatic = '.a{[' . implode(', ', $returnStatic) . ']}';
        return $returnStatic;
    }

?>