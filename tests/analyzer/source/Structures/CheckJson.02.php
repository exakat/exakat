<?php

json_encode(array('a' => 'b'));
json_encode(new StdClass('a'));

json_decode($a);
json_last_error_msg();

?>