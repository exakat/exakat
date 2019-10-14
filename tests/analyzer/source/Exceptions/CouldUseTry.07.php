<?php

timezone_open();
eval(1);

try{
timezone_open();
eval(1);
} catch (Exception $e) {}

svm::setoptions();
(new svm)->setoptions();

new simplexmlelement($a);

try {
    new simplexmlelement($b);
} catch( Exception $e) {

}

?>