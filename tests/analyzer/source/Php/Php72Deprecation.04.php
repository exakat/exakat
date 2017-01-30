<?php

assert('is_int($int1) /* $int parameter must be an int, not just numeric */');
assert('is_int($int2) '.'/* $int parameter must be an int, not just numeric */');
assert("is_int(\$int3) /* $int parameter must be an int, not just numeric */");
assert(<<<HEREDOC
heredoc
HEREDOC
);

assert(function ($x) { return is_int($int); /* $int parameter must be an int, not just numeric */});
assert(function ($x) { return is_int($int); /* $int parameter must be an int, not just numeric */}, 'a');
assert(1);


?>