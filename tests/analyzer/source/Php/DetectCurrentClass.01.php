<?php

$a = __CLASS__;
$b = __class__;
$b = B::__CLASS__;

get_called_class();
$a->get_called_class();

?>