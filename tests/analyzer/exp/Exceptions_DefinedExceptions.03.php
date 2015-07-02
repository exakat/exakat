<?php

$expected     = array('class a extends \Exception', 
                      'class ba extends a', 
                      'class cba extends ba', 
                      'class dcba extends cba', 
                      'class edcba extends dcba', 
                      'class fedcba extends edcba');

$expected_not = array();

?>