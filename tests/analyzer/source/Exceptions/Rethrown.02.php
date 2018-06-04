<?php

try {
    throw new Exception("ici");
} catch (NakedException $e) {
    throw $e;
}

try {
    throw new Exception("ici");
} catch (SecondException $e) {
    print "La";
    throw $e;
}


?>