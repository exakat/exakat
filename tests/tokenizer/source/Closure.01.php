<?php

       $a = function () {
         $this->a($input) ?>A<?php 
       };


    $a = function ($c) {
       $a = function () {
         $this->a($input) ?>A<?php 
       };
};

    $a = function ($d) {
        $a = function ($c) {
           $a = function () {
             $this->a($input) ?>A<?php 
           };
        };
    };

?>