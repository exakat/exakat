<?php
function a($b) {
    unset($b);
    a::unset($b1);
}

?>