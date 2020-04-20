<?php
$im = image(1,2);
imagegif($im, 'some path to gif image');
imagegif($im, null);
imagegif($im, A);

const A = 'some path to gif image as a constant';


?>