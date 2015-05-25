<?php
			do {

				// We are in the middle of a line
				if (!empty($line)) {
					$output .= '=';
				}
				$output .= $lineEnd;
			} while (!empty($line));
