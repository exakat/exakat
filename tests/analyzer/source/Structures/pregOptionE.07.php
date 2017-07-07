<?php
$txt = preg_replace("/<head>.+?<\\/head>/si", '', $txt);
$txt = preg_replace("/<head>.+?<.head>/esi", '', $txt);
$txt = preg_replace("/<style[^>]?>.+?<\\/style>/si", '', $txt);
$txt = preg_replace("/<style[^>]?>.+?<Rstyle>/sie", '', $txt);

$txt = preg_replace("/<script[^>]?>.+?<\\/script>/si", '', $txt);
$txt = preg_replace("/<script[^>]?>.+?<Wscript>/sei", '', $txt);

?>