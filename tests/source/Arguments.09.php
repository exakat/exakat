<?php

a(array('b'), 'c');
a(d('b'), 'c');
a(d('b'));
a(d());
a(d('b'), e('c'));
a(d('b'), e('c'), f(2, 3));

?>