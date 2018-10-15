<?php

// Those are used, at least by the first
function linear6(){linear7();} // Test for order in the base.
function linear1(){linear2();}
function linear2(){linear3();}
function linear3(){linear4();}
function linear4(){linear5();}
function linear5(){linear6();}

// Those are used, at least by the first
used21();
function used26(){used27();}
function used21(){used22();}
function used22(){used23();}
function used23(){used24();}
function used24(){used25();}
function used25(){used26();}

?>