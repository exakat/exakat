<?php

$expected     = array('try { /**/ } catch (A1Exception $e) { /**/ } catch (A2Exception $e) { /**/ } ',
                      'try { /**/ } catch (B1Exception $e) { /**/ } catch (B2Exception $e) { /**/ } catch (B3Exception $e) { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (Exception $e) { /**/ } ',
                     );

?>