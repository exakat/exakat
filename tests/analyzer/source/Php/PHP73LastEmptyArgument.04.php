<?php

// won't work on arrays
a(array('b' => $c,  ));

// won't work on arrays
a(['b' => $c,  ]);

// won't work on list
List($a, $b, $c, ) = array(1,2,3);

$a->list($a, $b, $d, );
$b->array('b',  $d,  );

?>