<?php

namespace X;

// Glob is locally redefined in X, so it is ignored
foreach(glob() as $x) {}

f(parse_ini_file('./someIni.ini'));

f($x->file('./someIni.ini'));
f(C::glob('./someIni.ini'));

1 + sqrt($w);

function glob() {}

?>