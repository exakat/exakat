<?php
try {
    something();
} catch( \Exception $e) {

} catch( \MyException $e) {

} catch( Throwable $e) {

} catch( \Throwable $e) {

}
?>