<?php

namespace Foo\Bar;

//constants 
echo UNQUALIFIED_CONSTANT ; 
echo SUBNAMESPACED\CONSTANTE; 
echo \FULL\NAMESPACED\CONSTANTE; 

// functions
unqualified_function() ; 
subnamespaced\fonction(); 
\full\namespaced\fonction(); 
staticallyCalling::staticallyCalledMethod();  // shoudln't be fullnspathed...
$object->objectCalledMethod();           // shoudln't be fullnspathed...

// classes
new FOO(); // resolves to constant Foo\Bar\FOO
new FOO; // resolves to constant Foo\Bar\FOO
fooclassc::FOOCSONTANT; // resolves to constant Foo\Bar\FOO
subnamespacec\fooclassc::FOOCSONTANT; // resolves to constant Foo\Bar\FOO
\fullc\namespacec\fooclassc::FOOCSONTANT; // resolves to constant Foo\Bar\FOO

fooclassp::$property; // resolves to constant Foo\Bar\FOO
subnamespacep\fooclassp::$property; // resolves to constant Foo\Bar\FOO
\fullp\namespacep\fooclassp::$property; // resolves to constant Foo\Bar\FOO

new subnamespace\FOO(); // resolves to constant Foo\Bar\subnamespace\FOO
new subnamespace\FOO; // resolves to constant Foo\Bar\subnamespace\FOO

fooclassm::staticMethod(); // resolves to constant Foo\Bar\FOO
subnamespacem\fooclassm::staticMethod(); // resolves to constant Foo\Bar\FOO
\fullm\namespacem\fooclassm::staticMethod(); // resolves to constant Foo\Bar\FOO

new \Foo\Bar\FOO(); // resolves to constant Foo\Bar\FOO
new \Foo\Bar\FOO; // resolves to constant Foo\Bar\FOO

//special case for instanceof 
$x instanceof fooclass; // resolves to constant Foo\Bar\FOO
$x instanceof subnamespace\fooclass; // resolves to constant Foo\Bar\FOO
$x instanceof \full\namespacepath\fooclass; // resolves to constant Foo\Bar\FOO

?>