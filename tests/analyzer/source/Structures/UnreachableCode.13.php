<?php

function foo( ) {
    try { 
        doSomething();
    } catch(Exception $e) {
        
    } finally {
        return 3;
    }
    ++$a;
}

function fooNoNext( ) {
    try { 
        doSomething();
    } catch(Exception $e) {
        
    } finally {
        throw new Exceptions;
    }
}

function fooMultipleCatch( ) {
    try { 
        doSomething();
    } catch(Exception $e) {
        
    } catch(Exception $e) {
        
    } catch(Exception $e) {
        return true;
    } catch(Exception $e) {
        
    } finally {
        --$a;
        --$b;
        throw new Exceptions;
    }
}

?>