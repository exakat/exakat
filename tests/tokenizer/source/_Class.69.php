<?php
/* implementing an anonymous console object from your framework maybe */
(new class extends ConsoleProgram {
    public function main() {
       /* ... */
    }
})->bootstrap();
 
/* return an anonymous implementation of a Page for your MVC framework */
return new class($controller) implements Page {
    public function __construct($controller) {
        /* ... */
    }
    /* ... */
};
 
/* vs */
class MyPage implements Page {
    public function __construct($controller) {
        /* ... */
    }
    /* ... */
}
return new MyPage($controller);
 
/* return an anonymous extension of the DirectoryIterator class */
return new class($path) extends DirectoryIterator {
   /* ... */
};
?>