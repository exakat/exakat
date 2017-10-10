<?php

if (!preg_match("/\d{4}-\d{2}-\d{2}/", $birthday)) {
    error('Wrong data format for your birthday!');
}

if (!preg_match("/^\d{4}-\d{2}-\d{3}/", $birthday)) {
    error('Wrong data format for your birthday!');
}

if (!preg_match("/php\d{4}-\d{2}-\d{4}$/", $birthday)) {
    error('Wrong data format for your birthday!');
}

if (!preg_match("/^\d{4}-\d{2}-\d{1}$/", $birthday)) {
    error('Wrong data format for your birthday!');
}

?>