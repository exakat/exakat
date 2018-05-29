<?php

try {
    throw new Exception("ici");
} catch (Exception $e) {
    throw $e;
}

try {
    throw new Exception("ici");
} catch (Exception $e) {
    print "La";
    throw $e;
} catch (Exception $b) {
    throw $b;
} catch (Exception $c) {
    throw $c;
}

try {
    throw new Exception("ici");
} catch (Exception $other) {
    throw new Exception("Other");
}

?>