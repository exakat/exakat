<?php

interface A extends B\C { function D(E $f); }

interface A extends B\C, G { function D(E $f); }

interface A extends B\C, G, I { function D(E $f); }

interface A extends B\C, G, I\H { function D(E $f); }

?>