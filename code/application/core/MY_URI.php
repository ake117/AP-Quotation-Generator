<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_URI extends CI_URI {

    public function __construct()   {
        parent::__construct();
    }

    /**
	 * Set URI String
	 *
	 * @param 	string	$str
	 * @return	void
	 */
	public function _set_uri_string($str)
	{
		// Filter out control characters and trim slashes
		$this->uri_string = trim(remove_invisible_characters($str, FALSE), '/');

		if ($this->uri_string !== '')
		{
			// Remove the URL suffix, if present
			if (($suffix = (string) $this->config->item('url_suffix')) !== '')
			{
				$slen = strlen($suffix);

				if (substr($this->uri_string, -$slen) === $suffix)
				{
					$this->uri_string = substr($this->uri_string, 0, -$slen);
				}
			}

			$this->segments[0] = NULL;
			// Populate the segments array
			foreach (explode('/', trim($this->uri_string, '/')) as $val)
			{
				$val = trim($val);
				// Filter segments for security
				$this->filter_uri($val);

				if ($val !== '')
				{
//					$this->segments[] = $val;
                                        $this->segments[] = urldecode($val); // support thai url
				}
			}

			unset($this->segments[0]);
		}
	}

         /**
	 * Fetch URI Segment
	 *
	 * @see		CI_URI::$segments
	 * @param	int		$n		Index
	 * @param	mixed		$no_result	What to return if the segment index is not found
	 * @return	mixed
	 */
	public function segment($n, $no_result = NULL, $search=NULL, $replace=NULL)
	{
            if($search!==NULL){
                $r = isset($this->segments[$n]) ? $this->segments[$n] : $no_result;
                return str_replace($search, $replace, $r);
            }else{
		return isset($this->segments[$n]) ? $this->segments[$n] : $no_result;
            }
	}

}