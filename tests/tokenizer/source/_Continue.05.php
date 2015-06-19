<?php
function a() {
    foreach (range(1, 3) as $i) {
        continue ?> A <?php
    }
}

var_dump(iterator_to_array(a()));
?>