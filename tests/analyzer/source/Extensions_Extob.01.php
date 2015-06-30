<?php

function callback($buffer) {
  return (str_replace("a", "b", $buffer));
}

ob_start("callback");

ob_end_flush();

?>