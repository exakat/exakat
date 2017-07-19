<?php
    interface emptyInterface {}
    interface nonEmptyInterface { function x () ;
                                  const x = 1;  }
    interface nonEmptyInterface2 { function x () ;
                                   function x2 () ;
                                   const x = 1;
                                   const x2 = 2;
                                   }
    interface nonEmptyInterface3 { function x () ;
                                   function x2 () ;
                                   function x3 () ;
                                   const x  = 3 ;
                                   const x2 = 3 ;
                                   const x3 = 3 ;
                                   }

    interface emptyExtendingInterface extends emptyInterface { }
?>