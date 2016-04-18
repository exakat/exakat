<?php
 
class Token {
	// Constants default to public
	const PUBLIC_CONST = 0;
 
        // Constants then also can have a defined visibility
        private const PRIVATE_CONST = 1;
        protected const PROTECTED_CONST = 2;
        public const PUBLIC_CONST_TWO = 3;
 
        //Constants can only have one visibility declaration list
        private const FOO = 4, BAR = 5;
}
 
 
//Interfaces only support public consts, and a compile time error will be thrown for anything else. Mirroring the behavior of methods.
interface ICache {
        public const PUBLIC = 6;
        const IMPLICIT_PUBLIC = 7;
}
 
//Reflection was enhanced to allow fetching more than just the values of constants
class testClass  {
  const TEST_CONST = 'test';
}
 
$obj = new ReflectionClass( "testClass" );
$const = $obj->getReflectionConstant( "TEST_CONST" );
$consts = $obj->getReflectionConstants();
