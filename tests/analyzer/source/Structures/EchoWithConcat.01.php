<?php

//normal and OK
echo "OK";
echo $OK;
echo '$KO';
echo <<<'NOWDOC'
nowdoc even with $var is fine.
NOWDOC;

//concatenation 
echo "$KO";
echo "should$be with comma";
echo 'should'.'also'.$be.' with comma';
echo <<<HEREDOC
should' too $be with comma
HEREDOC;

// fractal concat
echo "should". "really $be with comma";
echo "should", "really $be with", 'more comma';
echo $this, "should", "really $be with", 'commas';

// OK, with comma
echo "should", $be, " all", "ok";

?>