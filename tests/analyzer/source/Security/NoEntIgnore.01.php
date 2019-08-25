<?php

$new = htmlspecialchars("<a href='test'>Test</a>", ENT_IGNORE);

$new = htmlspecialchars("<a href='test'>Test</a>", \ENT_IGNORE);

$new = htmlspecialchars("<a href='test'>Test</a>", ENT_QUOTES);

function ent_ignore() {}

?>