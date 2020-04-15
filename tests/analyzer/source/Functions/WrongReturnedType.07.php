<?php

function (array $x): bool { return substr($x['analyzer'], 0, 7) !== 'Common';};
function (array $a): bool { return $a;};
function (array $a): bool { return $b + 1;};
function (array $a): bool { return $b->c[3] + 1;};

?>