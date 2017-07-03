<?php

function foo() {
    try {
        throw new \Exception();
    } catch (Exception $e) {
//        print 'Exception';
        return 'Exception';
    } finally {
        return 'Finally';
    }
}

function foo2() {
    try {
        throw new \Exception();
    } catch (Exception $e) {
        print 'Exception';
        return 'Exception';
    } finally {
//        return 'Finally';
    }
}

echo foo();
?>