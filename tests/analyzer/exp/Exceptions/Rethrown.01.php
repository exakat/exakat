<?php

$expected     = array('try { /**/ } catch (Exception $e) { /**/ } catch (Exception $b) { /**/ } catch (Exception $c) { /**/ } ',
                      'try { /**/ } catch (Exception $e) { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (Exception $other) { /**/ } ',
                     );

?>