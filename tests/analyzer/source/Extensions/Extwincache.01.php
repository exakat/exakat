<?php
    try {
        $results = wincache_ucache_get("products".$search_terms, $success);
    } catch (WincacheException $e) {
    
    }
    
    

?>