<?php
const CONST_DEFINE = 444;
define('CONST_DEFINE', 445);

preg_match( '/^http[s]?:\/\/(:' . CONST_DEFINE . ')?\/.*$/Ui', $r, $x);
preg_match( '/^http[s]?:\/\/(:' . 443 . ')?\/.*$/Ui', $r, $x);
preg_match( '/[' . CONST_CONST . '-44]', $r, $x);

?>