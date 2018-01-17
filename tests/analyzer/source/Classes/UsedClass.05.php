<?php
        class notInAUseWithAlias {} 
        use notInAUseWithAlias as b;

        class inAUseWithAlias2 {} 
        use inAUseWithAlias2 as b2;
        new b2();


?>