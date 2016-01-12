<?php

crypt( ); // Void
crypt(1);
crypt(1.1);
crypt(-2);
crypt(true);
crypt(false);
crypt(__FILE__);
crypt(Constante);
crypt(function ($a) {});
crypt(array(1,2));
crypt([13, 23]);
crypt([14, 24 + $s]);
crypt(
    strtolower(array(1,2))
);
crypt('A');
crypt('b'.'c');
crypt("D $e f");
crypt(<<<B

B
);
crypt(array(1,2,3));
crypt(<<<'BC'

BC
);


crypt($a);
crypt($b[1]);
crypt($c->d);
crypt($e->f());
crypt(G::H);
crypt(I::$J);
crypt(K::L());
crypt(m());


