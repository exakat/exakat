<?php
		if (!$index)
			while ($row = $this->fetch($fetchMode))
				$result[] = $row;
		else
			while ($row = $this->fetch($fetchMode)) {
				if (!isset($row[$index])) continue;
				$result[$row[$index]] = $row;
			}
