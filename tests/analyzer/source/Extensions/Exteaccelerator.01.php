<?php
	function set($id, $data, $lifetime = NULL)
	{
		if ($lifetime === NULL)
		{
			$lifetime = time() + Arr::get($this->_config, 'default_expire', Cache::DEFAULT_EXPIRE);
		}
		return eaccelerator_put($this->_sanitize_id($id), $data, $lifetime);
	}

    return eaccelerator_search($this->_sanitize_id($id), $data, $lifetime);
?>