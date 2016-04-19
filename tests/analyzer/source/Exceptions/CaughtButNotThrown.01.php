<?php

try {
    doSomething();
} catch( Undefined $nt) { // never thrown 

} catch( NotThrown $nt) { // never thrown 

} catch( DirectThrown $nt) { // thrown literally

} catch( Thrown $nt) {  // thrown as SubThrown

//} catch( Exception $nt) { // catch all

//} catch( Throwable $nt) { // catch all

}

throw new subsubThrown('message');
throw new DirectThrown('message');

class DirectThrown extends \Exception {}

class Thrown extends \Exception {}
class subThrown extends \Thrown {}
class subsubThrown extends \subThrown {}
class NotThrown extends \Exception {}


?>