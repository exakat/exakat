<?php

        class unusedClass {} 

        // class used in a New
        class inANew {} 
        new inANew();
        
        // classed used in a extends
        // class used in an implements
        class inAExtends {} 
        class inAImplements {} 
        class inAImplements1 {} 
        class inAImplements2 {} 
        class inAImplements3 {} 
        class someClass extends inAExtends implements inAImplements {} 
        class someClass2 implements inAImplements1, inAImplements2, inAImplements3 {} 
        new someClass();
        new someClass2();
        
        // class used in a staticmethodcall
        class inAStaticMethodcall {} 
        inAStaticMethodcall::methodcall();

        // class used in static property
        class inAStaticProperty {} 
        inAStaticProperty::$property;

        // class used in static constant
        class inAStaticConstant {} 
        inAStaticConstant::constante;

        // class use in a instanceof
        class inAInstanceof {} 
        $y instanceof inAInstanceof;

        // class used in a typehint
        class inATypehint {} 
        function x(inATypehint $y) {}

        // class used in a Use
        /*
        can't test in one file
        class inAUse {} 
        use inAUse;
        */

        class inAUseWithAlias {} 
        use inAUseWithAlias as b;
        class inAUseWithAlias2 {} 
        use inAUseWithAlias2;

        /*
        cannot test in one file
        class notInAUse {} 
        use c as notInAUse;
        
        */
    
        // class used in a String (full string only)
        class inAString {} 
        $y = 'inAString';

        class inAString2 {} 
        $y = 'inastring';
?>