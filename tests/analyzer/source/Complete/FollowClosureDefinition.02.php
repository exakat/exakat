<?php

(function(int $i1) {
    var_dump($i);
})('42');

($x = function(int $i2) {
    var_dump($i);
})('42');


function(int $i3) {
    var_dump($i);
};

?>