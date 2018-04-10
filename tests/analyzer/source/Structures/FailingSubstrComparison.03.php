<?php

substr($a, 0,1) == '\\';
substr($a, 0,1) == '\r';
substr($a, 0,1) == '\032';
substr($a, 0,1) == '\u{00002}';
substr($a, 0,1) == '\xaa';
substr($a, 0,1) == '\x66';
substr($a, 0,1) == '\p{Cc}';
substr($a, 0,1) == '\P{Cc}';
substr($a, 0,1) == '\XCc';
substr($a, 0,1) == 'bc';
substr($a, 0,1) == '\b';

?>