<?php

$expected     = array('try { /**/ } catch (B $b1) { /**/ } catch (C $c2) { /**/ } catch (A $a3) { /**/ } ',
                      'try { /**/ } catch (B $b1) { /**/ } catch (A $a2) { /**/ } catch (C $c3) { /**/ } ',
                      'try { /**/ } catch (A $a1) { /**/ } catch (B $b2) { /**/ } catch (C $c3) { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (C $c1) { /**/ } catch (A $a2) { /**/ } catch (B $b3) { /**/ } ',
                      'try { /**/ } catch (C $c1) { /**/ } catch (B $b2) { /**/ } catch (A $a3) { /**/ } ',
                      'try { /**/ } catch (A $a1) { /**/ } catch (C $c2) { /**/ } catch (B $b3) { /**/ } ',
                     );

?>