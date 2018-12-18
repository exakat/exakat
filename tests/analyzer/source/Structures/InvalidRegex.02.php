<?php

class x {
    const Y = ')(';
}

//preg_replace("/^([\.-\w]+)\/([\.-\w]+)(.*)$/i", '$1/$2', 'abc');
preg_match('#^' . $pattern . '$#' . $flags, 'abc');
preg_replace('/1' . preg_quote($m[0][$i], '/') . '/', '', 'abc');
preg_replace('/2' . $a->b . '/', '', 'abc');
preg_replace('/3' . $a['b'] . '/', '', 'abc');
preg_replace('/4' . $a{'b'} . '/', '', 'abc');
preg_replace('/5' . X::Y . '/', '', 'abc');
preg_replace('/6$a[b-d]/', '', 'abc');
//preg_match('/7\\u[0-9A-F]{4}/i', substr('abc', 'abc', 6));
preg_match('/8' . addcslashes($this->list_sep, '/') . '/i', 'abc');




?>