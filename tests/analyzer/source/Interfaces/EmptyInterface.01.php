<?php
    interface emptyInterface {}
    interface nonEmptyInterface { function x () ;}
    interface nonEmptyInterface2 { function x () ;
                                   function x2 () ;
                                   }
    interface nonEmptyInterface3 { function x () ;
                                   function x2 () ;
                                   function x3 () ;
                                   }

    interface emptyExtendingInterface extends emptyInterface { }
?>