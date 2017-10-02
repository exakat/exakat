<?php

//foreach($a as $this => $b) {}

$a = function () {
    echo $this;
};

$a = function () use ($THIS) {
    echo $this;
};


?>