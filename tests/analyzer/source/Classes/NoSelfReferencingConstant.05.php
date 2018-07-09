<?php

class x {
    const B = 1;
	const A		= self::B;
	const C		= self::B + self::C;
}

?>