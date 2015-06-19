<?php

interface A extends B\C { function D(E $f); }

interface A extends B\C, G\H { function D(E $f); }

interface A extends B\C, G\H, I\J { function D(E $f); }

?>