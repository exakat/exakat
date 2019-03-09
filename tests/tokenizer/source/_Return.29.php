<?php
function a() {
    foreach (range(1, 3) as $i) {
        return ?> A <?php
    }
}

echo a();
?>