<?php

try {
    // create FFI object, loading libc and exporting function printf()
    $ffi = FFI::cdef(
        "int printf(const char *format, ...);", // this is a regular C declaration
        "libc.so.6");
    // call C's printf()
    $ffi->printf("Hello %s!\n", "world");

    $ffi = fFi::cdef();
    $ffi = ffi::cdef();

} catch(ffi\parserexception $e) {
} catch(parserexception $e) {
}
?>