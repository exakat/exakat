<?php

class a { const B = 1;
            function d() { return 1;}
            static function e() { return 1;}
            }
$c =  new a();
		print $c->d() & a::B;
		$e = &a::e();
?>