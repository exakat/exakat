<?php

$expected     = array('try { /**/ } catch (Exception1 $e) { /**/ }  catch (Exception2 $e) { /**/ }  finally {;}');

$expected_not = array('try { /**/ } catch (Exception1 $e) { /**/ }  catch (Exception2 $e) { /**/ } ');

?>