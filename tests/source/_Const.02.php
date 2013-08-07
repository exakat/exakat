<?php

class x {
	const A = 1;
	const A = 1, B = '2';
	const A = 1, B = '2', C = D::E;
	const A = 1, B = '2', C = D::E, F = 4;
	const A = 1, B = '2', C = D::E, F = 4, G = 5;
	const A = 1, B = '2', C = D::E, F = 4, G = 5, I = 6;
	const A = 1, B = '2', C = D::E, F = 4, G = 5, I = 6, J = true;
}
?>