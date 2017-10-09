<?php

if (!preg_match("/\d{4}-\d{2}-\d{2}/s", $birthday)) {
    error('Wrong data format for your birthday!');
}

if (!preg_match("/^\d{4}-\d{2}-\d{3}/sd", $birthday)) {
    error('Wrong data format for your birthday!');
}

if (!preg_match("/php\d{4}-\d{2}-\d{4}$/dde", $birthday)) {
    error('Wrong data format for your birthday!');
}

if (!preg_match("/^\d{4}-\d{2}-\d{1}$/sux", $birthday)) {
    error('Wrong data format for your birthday!');
}

?>