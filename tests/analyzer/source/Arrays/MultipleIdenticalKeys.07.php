<?php

$a = array(0xB7 => 1, 
           183 => 2,
           0b10110111 => 3, 
           0267 => 4,
          '183' => 5,
          '183b' => 6
      );
var_dump($a);

$a = array(183 => 2,
          '183' => 5
      );
var_dump($a);

// No multiple
$a = array(183 => 2,
          '183b' => 5
      );
var_dump($a);

?>