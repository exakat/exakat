<?php

try {
    something();
} catch (someRethrownException $e0) {
    throw new someOtherException0($e0);
} catch (someRethrownException1 $e1) {
    throw new someOtherException1($a, $e1);
} catch (someRethrownException2 $e2) {
    throw new someOtherException1($a, $b, $e2);
} catch (someRethrownException3 $e3) {
    throw new someOtherException1($a, $b, $c, $e3);
} catch (someRethrownException4 $e4) {
    throw new someOtherException1($a, $b, $c, $d, $e4);
} catch (someRethrownException5 $e5) {
    throw new someOtherException1($a, $b, $c, $d, $e6);
}

?>