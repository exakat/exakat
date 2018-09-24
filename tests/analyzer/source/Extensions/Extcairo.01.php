<?php

$s = new CairoImageSurface(CairoFormat::ARGB32, 100, 100);
$c = new CairoContext($s);

new CairoMovie;

$c->setSourceRgb(0, 0, 0);
$c->paint();

$c->setLineWidth(1);
$c->setSourceRgb(1, 1, 1);

for ($r = 50; $r > 0; $r -= 10) {
 $c->arc(50, 50, $r, 0, 2 * M_PI);
 $c->stroke();
 $c->fill();
}

$s->writeToPng(dirname(__FILE__) . '/CairoContext__arc.png');
?>