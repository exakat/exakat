<?php

$a = 'abc';
$b = '人民';
$c = 'ไม้ไต่คู้';

function foo(){
    $a = 'utf-8';
    mb_stotolower('PHP', $a);
}

const C = 'iso-8859-1';
const D = 'iso-8859-9';
const E = 'iso-8859-2   '; // extra spaces

mb_stotolower('PHP', C);
mb_stotolower('PHP', $d);
mb_stotolower('PHP', 'iso-8859-9');

?>