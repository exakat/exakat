<?php
   # Works. No slashes around the /pattern/
   print_r( mb_split("\s", "hello world") );
   Array (
      [0] => hello
      [1] => world
   )

   # Doesn't work:
   print_r( mb_split("/\s/", "hello world") );
   Array (
      [0] => hello world
   )
?>