<?php
      function getArray() { return [1, 2, 3]; }

      $last = array_pop(getArray());

      $last = array_pop((getArray()));
      
      getArray([1,2,4] + ([3, 4] + [5 + 6]));
      
      $a = (1 + 3) * 4;
?>