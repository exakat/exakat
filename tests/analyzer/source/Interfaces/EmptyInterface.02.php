<?php
    interface emptyInterface {}
    interface nonEmptyInterface { const x = 1;}
    interface nonEmptyInterface2 { const x = 2 ;
                                   const x2 = 3 ;
                                   }
    interface nonEmptyInterface3 { const x  = 3 ;
                                   const x2 = 3 ;
                                   const x3 = 3 ;
                                   }

    interface emptyExtendingInterface extends emptyInterface { }
?>