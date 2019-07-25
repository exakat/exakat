<?php
substr($value, 0, 1) == "\"";
substr($value, 0, 1) == '\"';
substr($value, 0, 1) == "'";
substr($value, 0, 1) == '"';
substr($value, 0, 1) == "\a";
substr($value, 0, 1) == "\\a";
substr($value, 0, 1) == "\t";
substr($content, 0, 2) == "\0\0";
substr($content, 0, 2) === "\037\213";

const I = 2;
const S = <<<HEREDOC
\037\213
HEREDOC;
substr($content, 0, I) === S;

const I2 = 3;
const S2 = <<<HEREDOC
\037\213d
HEREDOC;
substr($content, 0, I) === S2;
substr($content, 0, I2) === S;


?>