<?php
  $ops = parsekit_compile_string('
echo "Foo\n";
', $errors, PARSEKIT_QUIET);

  var_dump($PARSEKIT_QUIET);
  
?>
