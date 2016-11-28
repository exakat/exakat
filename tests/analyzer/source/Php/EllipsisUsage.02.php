<?php

//array('Variable', 'Property', 'Staticproperty', 'Staticconstant', 'Methodcall', 'Staticmethodcall', 'Functioncall', 'Identifier', 'Nsname')

foo(...$a);
foo(...$a->b);
foo(...$a->b());
foo(...a::b);
foo(...a::$b);
foo(...a::b());
foo(...a());
foo(...a);
foo(...\a);

FOO($A);
FOO($A->B);
FOO($A->B());
FOO(A::B);
FOO(A::$B);
FOO(A::B());
FOO(A());
FOO(A);
FOO(\A);


?>