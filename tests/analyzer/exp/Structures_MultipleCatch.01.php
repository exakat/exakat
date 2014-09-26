<?php

$expected     = array('try { /**/ } catch (B3Exception $e) { /**/ }  catch (B2Exception $e) { /**/ }  catch (B1Exception $e) { /**/ } ',
                      'try { /**/ } catch (A2Exception $e) { /**/ }  catch (A1Exception $e) { /**/ } ');

$expected_not = array('try { /**/ } catch (Exception $e) { /**/ } ');

?>