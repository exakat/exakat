<?php
list(,$a, $b, ) = range(0, 2);
list($a, ,$b, ) = range(0, 2);
list($a,,,$b) = range(0, 2);
list($a,$b,,) = range(0, 2);
list(,,$a,$b) = range(0, 2);
list(,,$a,$b,,) = range(0, 2);
?>