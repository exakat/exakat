<?php
      function getArray() { return [1, 2, 3]; }

      $last = array_pop(getArray());

      $last = array_pop((getArray()));
?>