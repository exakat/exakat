<?php

try {
    something();
} catch (someRethrownException $e) {
    throw new someOtherException($message, $code, $e);
} catch (someRethrownException2 $e) {
    throw new someOtherException2($message, $code);
} catch (someRethrownException3 $e) {
    throw new someOtherException3($message);
} catch (someRethrownException4 $e) {
    throw new someOtherException3($message, $code, $f);
} catch (someFixedException $e) {
    fix();
}

?>