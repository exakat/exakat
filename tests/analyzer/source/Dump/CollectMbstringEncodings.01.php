<?php

$a = 'abc';
$b = '人民';
$c = 'ไม้ไต่คู้';

mb_stotolower('PHP', 'utf-8');
mb_stotolower('PHP', 'iso-8859-1');
mb_stotolower('PHP', 'LATIN1');
mb_stotolower('PHP', 'utf-8');

echo $c;

?>