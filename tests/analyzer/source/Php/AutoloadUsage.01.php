<?php
spl_autoload_register(function ($d) {
    include 'a://b/' . c('_', '/', $d) . '.php';
});

?>