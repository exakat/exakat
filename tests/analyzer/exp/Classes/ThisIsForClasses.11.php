<?php

$expected     = array('$this', 
                      '$this', 
                      '$this', 
                      '$this', 
                      '$this', 
                      '$this', 
                      '$this', 
                      '$this');

$expected_not = array('$this',  // Two of them are wrong (it's a method)
                      '$this',  
                      );

?>