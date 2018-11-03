<?php
   define('AbcDe\UNUSED', 1);

   define('AbcDe\FgG\USED1', 2);
   define('AbcDe\FgG\USED2', 3);
   define('AbcDe\FgG\USED3', 4);
   
   echo AbcDe\FgG\USED1;
   echo abcde\fgg\USED2;
   echo ABCDE\FGG\USED3;

   echo ABCDE\FGG\defined;
?>