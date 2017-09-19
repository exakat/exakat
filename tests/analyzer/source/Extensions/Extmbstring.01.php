<?php
   # Works. No slashes around the /pattern/
   print_r( mb_split("\s", "hello world") );

   # Doesn't work:
   print_r( mb_split("/\s/", "hello world") );
   $a->mb_strtolower("/\s/");
   
?>