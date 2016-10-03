<?php
 try {
    throw new ThrownException();
 } catch (Exception $e) {
 
 } catch (Exception2 $e) {
 
 } catch (\MyException $e) {
 
 }
?>  