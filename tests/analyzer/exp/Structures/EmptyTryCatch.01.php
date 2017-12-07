<?php

$expected     = array('try { /**/ } catch (Exception $e11) { /**/ } ',
                      'try { /**/ } catch (Exception $e1) { /**/ } catch (Exception $e12) { /**/ } catch (Exception $e13) { /**/ } ',
                      'try { /**/ } catch (Exception $e) { /**/ } catch (Exception $e2) { /**/ } catch (Exception $e3) { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (Exception $f11) { /**/ } ',
                     );

?>