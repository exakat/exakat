<?php


echo date('F', strtotime('+1 month',mktime(0,0,0,10,31,2017))).PHP_EOL;
echo date('F', strtotime('+2 month',mktime(0,0,0,10,31,2017))).PHP_EOL;
echo date('F', strtotime('-1 month',mktime(0,0,0,10,31,2017))).PHP_EOL;
echo date('F', strtotime("+$x month",mktime(0,0,0,10,31,2017))).PHP_EOL;
echo date('F', strtotime("+$x day",mktime(0,0,0,10,31,2017))).PHP_EOL;
echo date('F', strtotime("next month",mktime(0,0,0,10,31,2017))).PHP_EOL;

echo date('F', strtotime("first day of next month",mktime(0,0,0,10,31,2017))).PHP_EOL;
echo date('F', strtotime("last day of NEXT month",mktime(0,0,0,10,31,2017))).PHP_EOL;


?>