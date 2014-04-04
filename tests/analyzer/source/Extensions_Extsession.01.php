<?php 
    // Get the private context 
    session_name('Private'); 
    session_start(); 
    $private_id = session_id(); 
    $b = $_SESSION['pr_key']; 
    session_write_close(); 
    
    // Get the global context 
    session_name('Global'); 
    session_id('TEST'); 
    session_start(); 
    
    $a = $_SESSION['key']; 
    session_write_close(); 

    // Work & modify the global & private context (be ware of changing the global context!) 
 ?> 