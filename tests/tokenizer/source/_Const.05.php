<?php
 
class c {
	// Constants default to public
	const PUBLIC_CONST = 0;
 
        // Constants then also can have a defined visibility
        private const PRIVATE_CONST = 1, PRIVATE_CONST2 = 2, PRIVATE_CONST3 = 3;
        protected const PROTECTED_CONST = 2, PROTECTED_CONST2 = 2, PROTECTED_CONST3 = 3;
        public const PUBLIC_CONST_TWO = 3, PUBLIC_CONST_TWO3 = 3, PUBLIC_CONST_TWO4 = 3;
}
 
 
//Interfaces only support public consts, and a compile time error will be thrown for anything else. Mirroring the behavior of methods.
interface ICache {
        public const PUBLIC = 6, public2 = 7, public3 = 8;
}
