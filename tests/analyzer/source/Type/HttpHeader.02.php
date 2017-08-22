<?php

$good = <<<HEADER
Content-Type: application/octet-stream
HEADER;
$good2 = <<<'HEADER'
Max-Forwards: 34
custom-header: 33
custom-header-2: 34
HEADER;

$bad = <<<NOTHEADER
Transfer-Encoding UTF-8
NOTHEADER;

$bad2 = <<<'NOTHEADER'
normal string
NOTHEADER;

?>