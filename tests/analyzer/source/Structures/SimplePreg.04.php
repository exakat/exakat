<?php

preg_replace('/\^\w/', '', $vatNumber);
preg_replace('/\W/i', '_', $id);
preg_replace('/abc{3,2}/', $r, $b);

preg_replace("\u{00A0} ", '/  /', $msg);
preg_quote('Psr\Log', '/');
@preg_match('/\pL/u', 'a');
preg_replace('~\R~u', "\r\n", $expected);

?>