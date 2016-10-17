<?php 

substr($a, 0, 2) == 'ab';
substr($a, 0, 2) == 'abc';

substr($a, 1, 2) == 'ac';
substr($a, 1, 2) == 'acc';

substr($a, 1, -2) == 'ad';
substr($a, 1, -2) == 'adc';

'ae' == substr($a, 1, -2);
'aec' == substr($a, 1, -2);

substr($a, 1, -2) === 'af';
substr($a, 1, -2) === 'afc';

// Wrong
// Concatenation
substr($a, 0, 2) == "ab".'C';

// Substr with variables
substr($a, $b, 2) == "ab";
substr($a, 0, $b) == "ab";

// left with variables
substr($a, $b, 2) == "a$b2";

// Not a comparison
substr($a, 1, -2) !== 'ag';
substr($a, 1, -2) !== 'agc';


?>
