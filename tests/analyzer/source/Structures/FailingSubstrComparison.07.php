<?php 

const TWO = 2;

substr($a, 0, TWO) == 'ab';
substr($a, 0, TWO) == 'abc';

substr($a, 1, TWO) == 'ac';
substr($a, 1, TWO) == 'acc';

substr($a, 1, -TWO) == 'ad';
substr($a, 1, -TWO) == 'adc';

'ae' == substr($a, 1, -TWO);
'aec' == substr($a, 1, -TWO);

substr($a, 1, -TWO) === 'af';
substr($a, 1, -TWO) === 'afc';

// Wrong
// Concatenation
substr($a, 0, TWO) == "ab".'C';

// Substr with variables
substr($a, $b, TWO) == "ab";
substr($a, 0, $b) == "ab";

// left with variables
substr($a, $b, TWO) == "a$b2";

// Not a comparison
substr($a, 1, -TWO) !== 'ag';
substr($a, 1, -TWO) !== 'agc';


?>
